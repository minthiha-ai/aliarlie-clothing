<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->whereIn('page', ['home', 'default'])
                    ->orWhereNull('page');
            })
            ->latest()
            ->limit(5)
            ->get();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $bestSellers = Product::query()
            ->where('is_active', true)
            ->with([
                'images' => function ($query) {
                    $query->orderByDesc('is_primary');
                },
            ])
            ->latest()
            ->limit(6)
            ->get();

        return view('pages.index', compact('banners', 'categories', 'bestSellers'));
    }
}

