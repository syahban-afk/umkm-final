<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        // Dapatkan ID kategori yang digunakan oleh produk milik admin
        $productIds = Product::where('admin_id', Auth::id())->pluck('id');
        $categoryIds = Product::where('admin_id', Auth::id())
            ->pluck('product_category_id')
            ->unique();

        $categories = ProductCategory::latest()->paginate(10);

        // Tandai kategori yang digunakan oleh admin
        $categories->getCollection()->transform(function ($category) use ($categoryIds) {
            $category->used_by_admin = $categoryIds->contains($category->id);
            return $category;
        });

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['admin_id'] = Auth::id();

        ProductCategory::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(ProductCategory $category)
    {
        // Verifikasi bahwa kategori ini dibuat oleh admin yang login
        // atau digunakan oleh produk milik admin yang login
        $productIds = Product::where('admin_id', Auth::id())->pluck('id');
        $categoryUsedByAdmin = Product::whereIn('id', $productIds)
            ->where('product_category_id', $category->id)
            ->exists();

        if (!$categoryUsedByAdmin && $category->admin_id !== Auth::id()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit kategori ini');
        }

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, ProductCategory $category)
    {
        // Verifikasi bahwa kategori ini dibuat oleh admin yang login
        // atau digunakan oleh produk milik admin yang login
        $productIds = Product::where('admin_id', Auth::id())->pluck('id');
        $categoryUsedByAdmin = Product::whereIn('id', $productIds)
            ->where('product_category_id', $category->id)
            ->exists();

        if (!$categoryUsedByAdmin && $category->admin_id !== Auth::id()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate kategori ini');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(ProductCategory $category)
    {
        // Verifikasi bahwa kategori ini dibuat oleh admin yang login
        if ($category->admin_id !== Auth::id()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus kategori ini');
        }

        // Periksa apakah kategori memiliki produk terkait
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk terkait');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
