<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactStoreRequest;
use App\Models\Banner;
use App\Models\ContactInfo;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function faq(): View
    {
        return view('pages.faq');
    }

    public function contact(): View
    {
        $banner = Banner::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->whereIn('page', ['contact', 'default'])
                    ->orWhereNull('page');
            })
            ->latest()
            ->first();

        $contactInfo = ContactInfo::query()->first();

        return view('pages.contact', [
            'banner' => $banner,
            'contactInfo' => $contactInfo,
        ]);
    }

    public function contactStore(ContactStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Thank you for your message. We will get back to you soon.');
    }
}
