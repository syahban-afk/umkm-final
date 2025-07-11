<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\DiscountCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    /**
     * Display a listing of the discounts.
     */
    public function index()
    {
        // Dapatkan diskon milik admin yang login
        $discounts = Discount::with('category')
            ->where('admin_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('admin.discounts.index', compact('discounts'));
    }


    /**
     * Show the form for creating a new discount.
     */
    public function create()
    {
        // Ambil kategori diskon milik admin yang login atau yang belum dimiliki admin manapun
        $categories = DiscountCategory::where('admin_id', Auth::id())
            ->orWhereNull('admin_id')
            ->get();

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

        $data = $request->all();
        $data['admin_id'] = Auth::id();

        Discount::create($data);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Diskon berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified discount.
     */
    public function edit(Discount $discount)
    {
        // Verifikasi bahwa diskon ini digunakan oleh produk milik admin yang login
        // atau diskon ini dibuat oleh admin yang login
        $productIds = Product::where('admin_id', Auth::id())->pluck('id');
        $discountUsedByAdmin = Product::whereIn('id', $productIds)
            ->where('discount_id', $discount->id)
            ->exists();

        if (!$discountUsedByAdmin && $discount->admin_id !== Auth::id()) {
            return redirect()->route('admin.discounts.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit diskon ini');
        }

        // Ambil kategori diskon milik admin yang login atau yang belum dimiliki admin manapun
        $categories = DiscountCategory::where('admin_id', Auth::id())
            ->orWhereNull('admin_id')
            ->get();

        return view('admin.discounts.edit', compact('discount', 'categories'));
    }

    /**
     * Update the specified discount in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        // Verifikasi bahwa diskon ini digunakan oleh produk milik admin yang login
        // atau diskon ini dibuat oleh admin yang login
        $productIds = Product::where('admin_id', Auth::id())->pluck('id');
        $discountUsedByAdmin = Product::whereIn('id', $productIds)
            ->where('discount_id', $discount->id)
            ->exists();

        if (!$discountUsedByAdmin && $discount->admin_id !== Auth::id()) {
            return redirect()->route('admin.discounts.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate diskon ini');
        }

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
        // Verifikasi bahwa diskon ini dibuat oleh admin yang login
        if ($discount->admin_id !== Auth::id()) {
            return redirect()->route('admin.discounts.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus diskon ini');
        }

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
        // Ambil semua ID kategori yang digunakan oleh diskon milik admin saat ini
        $categoryIds = Discount::where('admin_id', Auth::id())
            ->pluck('discount_category_id')
            ->unique();

        // Ambil kategori-kategori tersebut saja
        $categories = DiscountCategory::whereIn('id', $categoryIds)
            ->latest()
            ->paginate(10);

        // Tandai bahwa kategori ini digunakan oleh admin
        $categories->getCollection()->transform(function ($category) {
            $category->used_by_admin = true;
            return $category;
        });

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

        $data = $request->all();
        $data['admin_id'] = Auth::id();

        DiscountCategory::create($data);

        return redirect()->route('admin.discount-categories.index')
            ->with('success', 'Kategori diskon berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified discount category.
     */
    public function categoryEdit(DiscountCategory $category)
    {
        // Verifikasi bahwa kategori ini dibuat oleh admin yang login
        if ($category->admin_id !== Auth::id()) {
            return redirect()->route('admin.discount-categories.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit kategori diskon ini');
        }

        return view('admin.discounts.categories.edit', compact('category'));
    }

    /**
     * Update the specified discount category in storage.
     */
    public function categoryUpdate(Request $request, DiscountCategory $category)
    {
        // Verifikasi bahwa kategori ini dibuat oleh admin yang login
        if ($category->admin_id !== Auth::id()) {
            return redirect()->route('admin.discount-categories.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate kategori diskon ini');
        }

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
        // Verifikasi bahwa kategori ini dibuat oleh admin yang login
        if ($category->admin_id !== Auth::id()) {
            return redirect()->route('admin.discount-categories.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus kategori diskon ini');
        }

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
