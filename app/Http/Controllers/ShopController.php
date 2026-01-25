<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Collection;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index()
    {
        $banner = $this->bannerFor('shop');
        $categories = Category::query()
            ->where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();
        $products = Product::query()
            ->where('is_active', true)
            ->with([
                'images' => function ($query) {
                    $query->orderByDesc('is_primary');
                },
            ])
            ->latest()
            ->paginate(12);

        return view('shop.index', compact('products', 'banner', 'categories'));
    }

    public function product($id)
    {
        $banner = $this->bannerFor('shop');
        $product = Product::query()
            ->with([
                'category',
                'images' => function ($query) {
                    $query->orderByDesc('is_primary');
                },
                'variants.stock',
            ])
            ->findOrFail($id);

        $relatedProducts = Product::query()
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->with([
                'images' => function ($query) {
                    $query->orderByDesc('is_primary');
                },
            ])
            ->latest()
            ->limit(4)
            ->get();

        return view('shop.product', compact('product', 'relatedProducts', 'banner'));
    }

    public function cart()
    {
        $banner = $this->bannerFor('shop');
        $cartItems = collect(session('cart', []));

        $subtotal = $cartItems->sum(function (array $item): float {
            return ((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 0));
        });

        return view('shop.cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'banner' => $banner,
        ]);
    }

    public function addToCart(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
        ]);

        $product = Product::query()
            ->with(['images', 'variants.stock'])
            ->findOrFail($data['product_id']);

        $variant = null;

        if ($product->variants->isNotEmpty()) {
            $variantId = $data['variant_id'] ?? null;
            $variant = $variantId ? $product->variants->firstWhere('id', (int) $variantId) : null;

            if (! $variant) {
                $variant = $product->variants
                    ->first(fn ($item) => optional($item->stock)->quantity > 0);
            }

            if (! $variant) {
                return back()->withErrors([
                    'variant_id' => 'No available variants in stock.',
                ]);
            }

            $available = $this->availableStock($variant);
            $requested = (int) $data['quantity'];

            if ($available < $requested) {
                return back()->withErrors([
                    'quantity' => "Only {$available} item(s) available for the selected variant.",
                ]);
            }
        }

        $image = $product->images->first();
        $price = $variant
            ? ($variant->discount_price ?? $variant->price)
            : ($product->discount_price ?? $product->price);

        $variantLabel = $variant ? "{$variant->size} / {$variant->color}" : null;
        $cartKey = $variant ? "variant_{$variant->id}" : "product_{$product->id}";

        $cart = collect(session('cart', []));
        $existing = $cart->get($cartKey);
        $quantity = (int) $data['quantity'];

        if ($existing) {
            $quantity += (int) ($existing['quantity'] ?? 0);
        }

        if ($variant) {
            $available = $this->availableStock($variant);
            if ($quantity > $available) {
                $quantity = $available;

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => "Only {$available} item(s) available for the selected variant.",
                        ...$this->cartPayload($cart),
                    ], 422);
                }

                return back()->withErrors([
                    'quantity' => "Only {$available} item(s) available for the selected variant.",
                ]);
            }
        }

        $cart[$cartKey] = [
            'product_id' => $product->id,
            'variant_id' => $variant?->id,
            'name' => $product->name,
            'variant' => $variantLabel,
            'price' => (float) $price,
            'quantity' => $quantity,
            'image' => $image?->image_path,
        ];

        $cartCount = $cart->sum('quantity');

        session([
            'cart' => $cart->all(),
            'cart_count' => $cartCount,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Added to cart.',
                ...$this->cartPayload($cart),
            ]);
        }

        return back()->with('success', 'Added to cart.');
    }

    public function updateCart(Request $request): JsonResponse
    {
        $data = $request->validate([
            'cart_key' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = collect(session('cart', []));
        $cartKey = $data['cart_key'];

        if (! $cart->has($cartKey)) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }

        $item = $cart->get($cartKey);
        $requested = (int) $data['quantity'];

        if (! empty($item['variant_id'])) {
            $variant = ProductVariant::query()
                ->with('stock')
                ->find($item['variant_id']);

            if ($variant) {
                $available = $this->availableStock($variant);
                $requested = min($requested, $available);
            }
        }

        $item['quantity'] = $requested;
        $cart[$cartKey] = $item;

        $this->storeCart($cart);

        $payload = $this->cartPayload($cart);

        if ($requested !== (int) $data['quantity']) {
            $payload['message'] = "Quantity adjusted to available stock ({$requested}).";
        }

        return response()->json($payload);
    }

    public function removeCart(Request $request): JsonResponse
    {
        $data = $request->validate([
            'cart_key' => ['required', 'string'],
        ]);

        $cart = collect(session('cart', []));
        $cartKey = $data['cart_key'];

        if (! $cart->has($cartKey)) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }

        $cart = $cart->reject(fn ($value, $key) => $key === $cartKey);

        $this->storeCart($cart);

        return response()->json($this->cartPayload($cart));
    }

    private function storeCart($cart): void
    {
        $cartCount = $cart->sum('quantity');

        session([
            'cart' => $cart->all(),
            'cart_count' => $cartCount,
        ]);
    }

    private function cartPayload($cart): array
    {
        $subtotal = $cart->sum(function (array $item): float {
            return ((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 0));
        });

        return [
            'cart_count' => $cart->sum('quantity'),
            'subtotal' => $subtotal,
            'sidebar_cart' => view('partials.sidebar-cart')->render(),
            'cart_content' => view('partials.cart-content', [
                'cartItems' => $cart,
                'subtotal' => $subtotal,
            ])->render(),
        ];
    }

    private function availableStock(ProductVariant $variant): int
    {
        $quantity = (int) ($variant->stock->quantity ?? 0);
        $reserved = (int) ($variant->stock->reserved ?? 0);

        return max(0, $quantity - $reserved);
    }

    public function checkout()
    {
        $banner = $this->bannerFor('shop');
        if (! Auth::guard('customer')->check()) {
            return redirect()
                ->route('shop.account')
                ->with('error', 'Please login to continue checkout.');
        }

        $cartItems = collect(session('cart', []));

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('shop.cart')
                ->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function (array $item): float {
            return ((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 0));
        });

        $payments = Payment::query()
            ->where('status', 'active')
            ->where('payment_type', 'online_payment')
            ->orderBy('name')
            ->get();

        return view('shop.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'payments' => $payments,
            'banner' => $banner,
        ]);
    }

    public function placeOrder(CheckoutRequest $request): RedirectResponse
    {
        if (! Auth::guard('customer')->check()) {
            return redirect()
                ->route('shop.account')
                ->with('error', 'Please login to place an order.');
        }

        $cartItems = collect(session('cart', []));

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('shop.cart')
                ->with('error', 'Your cart is empty.');
        }

        $data = $request->validated();
        $customer = Auth::guard('customer')->user();

        $subtotal = $cartItems->sum(function (array $item): float {
            return ((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 0));
        });

        $paymentId = $data['payment_id'] ?? null;
        $paymentMethod = $data['payment_method'];
        $paymentProof = null;

        if ($paymentMethod === 'online_payment' && $paymentId === null) {
            return back()->with('error', 'Please choose a payment method.');
        }

        if ($paymentMethod === 'online_payment') {
            $paymentExists = Payment::query()
                ->where('id', $paymentId)
                ->where('payment_type', 'online_payment')
                ->where('status', 'active')
                ->exists();

            if (! $paymentExists) {
                return back()->with('error', 'Selected payment method is unavailable.');
            }
        }

        if ($request->hasFile('payment_proof_photo')) {
            $paymentProof = $request->file('payment_proof_photo')
                ->store('payments/proofs', 'public');
        }

        try {
            $order = DB::transaction(function () use (
                $cartItems,
                $customer,
                $data,
                $paymentId,
                $paymentMethod,
                $paymentProof,
                $subtotal
            ): Order {
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'order_code' => $this->generateOrderCode(),
                    'payment_id' => $paymentMethod === 'online_payment' ? $paymentId : null,
                    'payment_proof_photo' => $paymentProof,
                    'payment_method' => $paymentMethod,
                    'total_amount' => $subtotal,
                    'status' => 'pending',
                ]);

                $billing = [
                    'receiver_name' => trim($data['billing_first_name'].' '.$data['billing_last_name']),
                    'phone' => $data['billing_phone'],
                    'address_line1' => $data['billing_address_line1'],
                    'address_line2' => $data['billing_address_line2'] ?? null,
                    'township' => $data['billing_township'],
                    'city' => $data['billing_city'],
                    'postal_code' => $data['billing_postal_code'] ?? null,
                    'country' => $data['billing_country'],
                ];

                $shipping = $billing;

                if (! empty($data['ship_to_different'])) {
                    $shipping = [
                        'receiver_name' => trim($data['shipping_first_name'].' '.$data['shipping_last_name']),
                        'phone' => $data['shipping_phone'],
                        'address_line1' => $data['shipping_address_line1'],
                        'address_line2' => $data['shipping_address_line2'] ?? null,
                        'township' => $data['shipping_township'],
                        'city' => $data['shipping_city'],
                        'postal_code' => $data['shipping_postal_code'] ?? null,
                        'country' => $data['shipping_country'] ?? $data['billing_country'],
                    ];
                }

                OrderAddress::create(array_merge($billing, [
                    'order_id' => $order->id,
                    'type' => 'billing',
                ]));

                OrderAddress::create(array_merge($shipping, [
                    'order_id' => $order->id,
                    'type' => 'shipping',
                ]));

                CustomerAddress::firstOrCreate([
                    'customer_id' => $customer->id,
                    'receiver_name' => $billing['receiver_name'],
                    'phone' => $billing['phone'],
                    'address' => $billing['address_line1'],
                    'township' => $billing['township'],
                    'city' => $billing['city'],
                ]);

                foreach ($cartItems as $cartItem) {
                    $variantId = $cartItem['variant_id'] ?? null;

                    if (! $variantId) {
                        throw new \RuntimeException('Product variant is required to place an order.');
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $variantId,
                        'price' => (float) ($cartItem['price'] ?? 0),
                        'quantity' => (int) ($cartItem['quantity'] ?? 0),
                    ]);
                }

                return $order;
            });
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }

        session()->forget(['cart', 'cart_count']);

        return redirect()
            ->route('shop.account')
            ->with('success', 'Order placed successfully.');
    }

    private function generateOrderCode(): string
    {
        do {
            $code = sprintf(
                'ALIAR-%03d-%03d',
                random_int(0, 999),
                random_int(0, 999)
            );
        } while (Order::query()->where('order_code', $code)->exists());

        return $code;
    }

    public function wishlist()
    {
        $banner = $this->bannerFor('shop');

        return view('shop.wishlist', [
            'banner' => $banner,
        ]);
    }

    public function compare()
    {
        $banner = $this->bannerFor('shop');

        return view('shop.compare', [
            'banner' => $banner,
        ]);
    }

    public function account()
    {
        return view('shop.account');
    }

    public function collections()
    {
        $banner = $this->bannerFor('collection');

        $collections = Collection::query()
            ->where('is_active', true)
            ->withCount('products')
            ->with([
                'products' => function ($query) {
                    $query->where('is_active', true)
                        ->with([
                            'images' => function ($query) {
                                $query->orderByDesc('is_primary');
                            },
                        ])
                        ->limit(1);
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12);

        return view('shop.collections', [
            'banner' => $banner,
            'collections' => $collections,
        ]);
    }

    public function collection($slug)
    {
        $banner = $this->bannerFor('collection');

        $collection = Collection::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->withCount('products')
            ->firstOrFail();

        $products = $collection->products()
            ->where('is_active', true)
            ->with([
                'images' => function ($query) {
                    $query->orderByDesc('is_primary');
                },
                'category',
            ])
            ->paginate(12);

        return view('shop.collection', [
            'banner' => $banner,
            'collection' => $collection,
            'products' => $products,
        ]);
    }

    public function fullwidth()
    {
        $banner = $this->bannerFor('shop');

        return view('shop.fullwidth', [
            'banner' => $banner,
        ]);
    }

    private function bannerFor(string $page): ?Banner
    {
        return Banner::query()
            ->where('is_active', true)
            ->where(function ($query) use ($page) {
                $query
                    ->whereIn('page', [$page, 'default'])
                    ->orWhereNull('page');
            })
            ->latest()
            ->first();
    }
}
