<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::all();
        $query = Product::with(['category', 'discount']);

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('product_category_id', $request->category);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Apply discount filter
        if ($request->filled('discount') && $request->discount === 'active') {
            $today = Carbon::now();
            $query->whereHas('discount', function ($q) use ($today) {
                $q->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
            });
        }

        // Sort products
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);

        return view('shop.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'discount', 'reviews.user']);

        // Get active discount
        $activeDiscount = null;
        if ($product->discount) {
            $today = Carbon::now();
            if ($today->between($product->discount->start_date, $product->discount->end_date)) {
                $activeDiscount = $product->discount;
            }
        }

        // Get related products
        $relatedProducts = Product::where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'activeDiscount', 'relatedProducts'));
    }
}
