<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Discount;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil semua kategori untuk filter
        $categories = ProductCategory::all();

        // Query dasar untuk produk
        $productsQuery = Product::with(['category', 'discounts'])->latest();

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category != '') {
            $productsQuery->where('product_category_id', $request->category);
        }

        // Filter berdasarkan nama produk
        if ($request->has('search') && $request->search != '') {
            $productsQuery->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan diskon aktif
        if ($request->has('discount') && $request->discount == 'active') {
            $today = Carbon::now()->toDateString();
            $productsQuery->whereHas('discounts', function($query) use ($today) {
                $query->where('start_date', '<=', $today)
                      ->where('end_date', '>=', $today);
            });
        }

        // Mengambil produk dengan pagination
        $products = $productsQuery->paginate(12);

        return view('shop.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        // Load relasi yang diperlukan
        $product->load(['category', 'discounts']);

        // Cek apakah produk memiliki diskon aktif
        $today = Carbon::now()->toDateString();
        $activeDiscount = $product->discount()
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();

        // Produk terkait dari kategori yang sama
        $relatedProducts = Product::where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'activeDiscount', 'relatedProducts'));
    }
}
