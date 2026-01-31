<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerAddressRequest;
use App\Http\Requests\CustomerLoginRequest;
use App\Http\Requests\CustomerProfileRequest;
use App\Http\Requests\CustomerRegisterRequest;
use App\Models\Banner;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class CustomerAuthController extends Controller
{
    public function show(): View
    {
        $banner = Banner::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->whereIn('page', ['account', 'default'])
                    ->orWhereNull('page');
            })
            ->orderBy('sort_order')
            ->latest()
            ->first();

        $customer = Auth::guard('customer')->user();
        $orders = collect();
        $addresses = collect();

        if ($customer) {
            $customer = Customer::query()
                ->with('addresses')
                ->find($customer->id);

            $orders = $customer?->orders()
                ->with(['items.productVariant.product.images'])
                ->latest()
                ->limit(5)
                ->get() ?? collect();
            $addresses = $customer?->addresses ?? collect();
        }

        return view('shop.account', [
            'customer' => $customer,
            'orders' => $orders,
            'addresses' => $addresses,
            'banner' => $banner,
        ]);
    }

    public function login(CustomerLoginRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $login = $data['login'];
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $remember = $request->boolean('remember');

        if (Auth::guard('customer')->attempt([$field => $login, 'password' => $data['password'], 'status' => 'active'], $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('shop.account'))
                ->with('success', 'Welcome back!');
        }

        return back()
            ->withErrors(['login' => 'These credentials do not match our records.'])
            ->withInput($request->only('login', 'remember'));
    }

    public function register(CustomerRegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $customer = Customer::create([
            'name' => $data['register_name'],
            'phone' => $data['register_phone'],
            'email' => $data['register_email'],
            'password' => $data['register_password'],
            'status' => 'active',
        ]);

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        if ($customer->email) {
            $customer->sendEmailVerificationNotification();
        }

        return redirect()->route('shop.verification.notice')
            ->with('success', 'Account created. Please verify your email address.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Logged out successfully.');
    }

    public function googleOneTap(Request $request): JsonResponse
    {
        $data = $request->validate([
            'credential' => ['required', 'string'],
        ]);

        try {
            /** @var \Laravel\Socialite\Two\AbstractProvider $provider */
            $provider = Socialite::driver('google');
            $provider->stateless();
            $googleUser = $provider->userFromToken($data['credential']);
        } catch (\Throwable $exception) {
            return response()->json(['message' => 'Unable to verify Google credentials.'], 422);
        }

        $customer = $this->resolveCustomerFromGoogle($googleUser);

        if ($customer->status !== 'active') {
            return response()->json(['message' => 'This account is inactive.'], 403);
        }

        Auth::guard('customer')->login($customer, true);
        $request->session()->regenerate();

        if (! $customer->hasVerifiedEmail() && $customer->email) {
            $customer->sendEmailVerificationNotification();

            return response()->json([
                'message' => 'Please verify your email address.',
                'redirect' => route('shop.verification.notice'),
            ]);
        }

        return response()->json([
            'message' => 'Signed in with Google.',
            'redirect' => route('shop.account'),
        ]);
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();
        } catch (\Throwable $exception) {
            return redirect()->route('shop.account')
                ->withErrors(['login' => 'Unable to sign in with Google. Please try again.']);
        }

        $customer = $this->resolveCustomerFromGoogle($googleUser);

        if ($customer->status !== 'active') {
            return redirect()->route('shop.account')
                ->withErrors(['login' => 'This account is inactive.']);
        }

        Auth::guard('customer')->login($customer, true);
        $request->session()->regenerate();

        if (! $customer->hasVerifiedEmail() && $customer->email) {
            $customer->sendEmailVerificationNotification();

            return redirect()->route('shop.verification.notice')
                ->with('success', 'Please verify your email address.');
        }

        return redirect()->route('shop.account')
            ->with('success', 'Signed in with Google.');
    }

    public function verificationNotice(): View
    {
        $banner = Banner::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->whereIn('page', ['account', 'default'])
                    ->orWhereNull('page');
            })
            ->orderBy('sort_order')
            ->latest()
            ->first();

        return view('shop.verify-email', [
            'banner' => $banner,
        ]);
    }

    public function verifyEmail(Request $request, int $id, string $hash): RedirectResponse
    {
        /** @var Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        if (! $customer || $customer->id !== $id) {
            abort(403);
        }

        if (! hash_equals((string) $hash, sha1($customer->getEmailForVerification()))) {
            abort(403);
        }

        if ($customer->hasVerifiedEmail()) {
            return redirect()->route('shop.account');
        }

        if ($customer->markEmailAsVerified()) {
            event(new Verified($customer));
        }

        return redirect()->route('shop.account')
            ->with('success', 'Email verified successfully.');
    }

    public function resendVerification(Request $request): RedirectResponse
    {
        /** @var Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            return redirect()->route('shop.account');
        }

        if ($customer->hasVerifiedEmail()) {
            return redirect()->route('shop.account')
                ->with('success', 'Your email is already verified.');
        }

        $customer->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent.');
    }

    public function updateProfile(CustomerProfileRequest $request): RedirectResponse
    {
        /** @var Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            return redirect()->route('shop.account');
        }

        $data = $request->validated();
        $emailChanged = $data['email'] !== $customer->email;

        $customer->fill([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
        ]);

        if (! empty($data['password'])) {
            $customer->password = $data['password'];
        }

        if ($emailChanged) {
            $customer->email_verified_at = null;
        }

        $customer->save();

        if ($emailChanged && $customer->email) {
            $customer->sendEmailVerificationNotification();
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    private function resolveCustomerFromGoogle($googleUser): Customer
    {
        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();
        $name = $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User';
        $avatar = $googleUser->getAvatar();
        $emailVerified = (bool) data_get($googleUser->user, 'email_verified', false);

        $customer = Customer::query()
            ->when($googleId, fn ($query) => $query->orWhere('google_id', $googleId))
            ->when($email, fn ($query) => $query->orWhere('email', $email))
            ->first();

        if (! $customer) {
            $phone = $googleId ? "google-{$googleId}" : 'google-'.Str::random(12);

            while (Customer::query()->where('phone', $phone)->exists()) {
                $phone = 'google-'.Str::random(16);
            }

            return Customer::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => Str::random(32),
                'status' => 'active',
                'google_id' => $googleId,
                'google_avatar' => $avatar,
                'email_verified_at' => $emailVerified ? now() : null,
            ]);
        }

        $updates = [
            'name' => $customer->name ?: $name,
            'google_id' => $customer->google_id ?: $googleId,
            'google_avatar' => $avatar ?? $customer->google_avatar,
        ];

        if (! $customer->email && $email) {
            $updates['email'] = $email;
        }

        if ($emailVerified && ! $customer->email_verified_at) {
            $updates['email_verified_at'] = now();
        }

        $customer->fill($updates)->save();

        return $customer;
    }

    public function storeAddress(CustomerAddressRequest $request): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            return redirect()->route('shop.account');
        }

        $data = $request->validated();

        CustomerAddress::create([
            'customer_id' => $customer->id,
            'receiver_name' => $data['receiver_name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'township' => $data['township'],
            'city' => $data['city'],
        ]);

        return back()->with('success', 'Address added successfully.');
    }

    public function ordersIndex(): View
    {
        $banner = Banner::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->whereIn('page', ['account', 'default'])
                    ->orWhereNull('page');
            })
            ->orderBy('sort_order')
            ->latest()
            ->first();

        /** @var Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            abort(403);
        }

        $orders = $customer->orders()
            ->with(['items.productVariant.product'])
            ->latest()
            ->get();

        return view('shop.orders.index', [
            'orders' => $orders,
            'banner' => $banner,
        ]);
    }

    public function orderShow(int $orderId): View
    {
        $banner = Banner::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->whereIn('page', ['account', 'default'])
                    ->orWhereNull('page');
            })
            ->orderBy('sort_order')
            ->latest()
            ->first();

        /** @var Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            abort(403);
        }

        $order = $customer->orders()
            ->with(['items.productVariant.product', 'addresses'])
            ->findOrFail($orderId);

        return view('shop.orders.show', [
            'order' => $order,
            'banner' => $banner,
        ]);
    }

    public function addresses(): View
    {
        $banner = Banner::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->whereIn('page', ['account', 'default'])
                    ->orWhereNull('page');
            })
            ->orderBy('sort_order')
            ->latest()
            ->first();

        /** @var Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            abort(403);
        }

        $addresses = $customer->addresses()->latest()->get();

        return view('shop.account.addresses', [
            'customer' => $customer,
            'addresses' => $addresses,
            'banner' => $banner,
        ]);
    }

    public function payments(): View
    {
        $banner = Banner::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->whereIn('page', ['account', 'default'])
                    ->orWhereNull('page');
            })
            ->orderBy('sort_order')
            ->latest()
            ->first();

        /** @var Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            abort(403);
        }

        return view('shop.account.payments', [
            'customer' => $customer,
            'banner' => $banner,
        ]);
    }

    public function returns(): View
    {
        $banner = Banner::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->whereIn('page', ['account', 'default'])
                    ->orWhereNull('page');
            })
            ->orderBy('sort_order')
            ->latest()
            ->first();

        /** @var Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            abort(403);
        }

        return view('shop.account.returns', [
            'customer' => $customer,
            'banner' => $banner,
        ]);
    }

    public function cancellations(): View
    {
        $banner = Banner::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->whereIn('page', ['account', 'default'])
                    ->orWhereNull('page');
            })
            ->orderBy('sort_order')
            ->latest()
            ->first();

        /** @var Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            abort(403);
        }

        return view('shop.account.cancellations', [
            'customer' => $customer,
            'banner' => $banner,
        ]);
    }

    public function reviews(): View
    {
        $banner = Banner::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->whereIn('page', ['account', 'default'])
                    ->orWhereNull('page');
            })
            ->orderBy('sort_order')
            ->latest()
            ->first();

        /** @var Customer|null $customer */
        $customer = Auth::guard('customer')->user();

        if (! $customer) {
            abort(403);
        }

        return view('shop.account.reviews', [
            'customer' => $customer,
            'banner' => $banner,
        ]);
    }
}
