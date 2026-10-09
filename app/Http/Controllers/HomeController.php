<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\HeroSetting;
use App\Models\BrandFeature;
use App\Models\AboutPage;

class HomeController extends Controller
{
    public function index()
    {
        
        $hero = HeroSetting::first();

        // Ambil 6 produk aktif terbaru
        $products = Product::where('is_active', true)
            ->with(['category', 'variants']) // Eager loading biar cepat
            ->withCount('variants')
            ->latest()
            ->take(6)
            ->get();

        // Ambil 3 artikel terbaru
        $articles = Article::whereNotNull('published_at')
            ->latest('published_at')
            ->take(6)
            ->get();
        
        $features = BrandFeature::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $about = AboutPage::first();

        return view('home', compact('products', 'articles', 'hero', 'features', 'about'));
    }
}