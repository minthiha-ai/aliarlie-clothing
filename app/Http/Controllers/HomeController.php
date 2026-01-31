<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::query()
            ->with('product')
            ->where('is_active', true)
            ->where(function ($query) {
                $query
                    ->whereIn('page', ['home', 'default'])
                    ->orWhereNull('page');
            })
            ->orderBy('sort_order')
            ->latest()
            ->limit(5)
            ->get();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $collections = Collection::query()
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(6)
            ->get();

        $bestSellers = Product::query()
            ->where('is_active', true)
            ->with([
                'images' => function ($query) {
                    $query->orderByDesc('is_primary');
                },
            ])
            ->orderBy('sort_order')
            ->latest()
            ->limit(6)
            ->get();

        return view('pages.index', compact('banners', 'categories', 'collections', 'bestSellers'));
    }
}
