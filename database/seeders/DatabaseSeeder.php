<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        Banner::factory()->count(4)->create();

        $payments = Payment::factory()->count(3)->create();
        $categories = Category::factory()->count(5)->create();

        $products = $categories->flatMap(function (Category $category) {
            return Product::factory()->count(6)->create([
                'category_id' => $category->id,
            ]);
        });

        $products->each(function (Product $product) {
            ProductImage::factory()->count(2)->create([
                'product_id' => $product->id,
            ]);

            ProductVariant::factory()->count(3)->create([
                'product_id' => $product->id,
            ]);
        });

        $variants = ProductVariant::query()->get();

        $customers = Customer::factory()->count(8)->create();

        $customers->each(function (Customer $customer) use ($variants, $payments) {
            CustomerAddress::factory()->count(2)->create([
                'customer_id' => $customer->id,
            ]);

            $cart = Cart::factory()->create([
                'customer_id' => $customer->id,
            ]);

            CartItem::factory()->count(2)->create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variants->random()->id,
            ]);

            $orders = Order::factory()->count(2)->create([
                'customer_id' => $customer->id,
            ]);

            $orders->each(function (Order $order) use ($variants, $payments) {
                $paymentMethod = $order->payment_method;

                if ($paymentMethod === 'online_payment') {
                    $order->update([
                        'payment_id' => $payments->random()->id,
                        'payment_proof_photo' => 'payments/proofs/'.$order->id.'.jpg',
                    ]);
                }

                OrderAddress::factory()->create([
                    'order_id' => $order->id,
                    'type' => 'shipping',
                ]);

                if (fake()->boolean(50)) {
                    OrderAddress::factory()->create([
                        'order_id' => $order->id,
                        'type' => 'billing',
                    ]);
                }

                for ($i = 0; $i < 2; $i++) {
                    $variant = $variants->random();

                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_variant_id' => $variant->id,
                        'price' => $variant->discount_price ?? $variant->price,
                    ]);
                }

                $order->recalculateTotalAmount();
            });
        });
    }
}
