<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\DiscountCategory;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of the discounts.
     */
    public function index()
    {
        $discounts = Discount::with('category')->latest()->paginate(10);
        return view('admin.discounts.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new discount.
     */
    public function create()
    {
        $categories = DiscountCategory::all();
        return view('admin.discounts.create', compact('categories'));
    }

    /**
     * Store a newly created discount in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'discount_category_id' => 'required|exists:discount_categories,id',
        ]);

        Discount::create($request->all());

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Diskon berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified discount.
     */
    public function edit(Discount $discount)
    {
        $categories = DiscountCategory::all();
        return view('admin.discounts.edit', compact('discount', 'categories'));
    }

    /**
     * Update the specified discount in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'discount_category_id' => 'required|exists:discount_categories,id',
        ]);

        $discount->update($request->all());

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Diskon berhasil diperbarui');
    }

    /**
     * Remove the specified discount from storage.
     */
    public function destroy(Discount $discount)
    {
        // Periksa apakah diskon digunakan oleh produk
        if ($discount->products()->count() > 0) {
            return redirect()->route('admin.discounts.index')
                ->with('error', 'Diskon tidak dapat dihapus karena masih digunakan oleh produk');
        }

        $discount->delete();

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Diskon berhasil dihapus');
    }

    /**
     * Display a listing of the discount categories.
     */
    public function categoryIndex()
    {
        $categories = DiscountCategory::latest()->paginate(10);
        return view('admin.discounts.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new discount category.
     */
    public function categoryCreate()
    {
        return view('admin.discounts.categories.create');
    }

    /**
     * Store a newly created discount category in storage.
     */
    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DiscountCategory::create($request->all());

        return redirect()->route('admin.discount-categories.index')
            ->with('success', 'Kategori diskon berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified discount category.
     */
    public function categoryEdit(DiscountCategory $category)
    {
        return view('admin.discounts.categories.edit', compact('category'));
    }

    /**
     * Update the specified discount category in storage.
     */
    public function categoryUpdate(Request $request, DiscountCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.discount-categories.index')
            ->with('success', 'Kategori diskon berhasil diperbarui');
    }

    /**
     * Remove the specified discount category from storage.
     */
    public function categoryDestroy(DiscountCategory $category)
    {
        // Periksa apakah kategori diskon digunakan oleh diskon
        if ($category->discounts()->count() > 0) {
            return redirect()->route('admin.discount-categories.index')
                ->with('error', 'Kategori diskon tidak dapat dihapus karena masih digunakan oleh diskon');
        }

        $category->delete();

        return redirect()->route('admin.discount-categories.index')
            ->with('success', 'Kategori diskon berhasil dihapus');
    }
}
