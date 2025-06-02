<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'discount'])
            ->where('admin_id', Auth::id()) // Filter berdasarkan admin yang login
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $discounts = Discount::with('category')->get();
        return view('admin.products.create', compact('categories', 'discounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'product_category_id' => 'required|exists:product_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'discount_id' => 'nullable|exists:discounts,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'product_category_id' => $validated['product_category_id'],
            'description' => $validated['description'],
            'discount_id' => $validated['discount_id'] ?? null,
            'admin_id' => Auth::id(),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        if ($product->admin_id !== Auth::id()) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit produk ini');
        }

        $categories = ProductCategory::all();
        $discounts = Discount::with('category')->get();
        return view('admin.products.edit', compact('product', 'categories', 'discounts'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->admin_id !== Auth::id()) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate produk ini');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'product_category_id' => 'required|exists:product_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'discount_id' => 'nullable|exists:discounts,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'product_category_id' => $validated['product_category_id'],
            'description' => $validated['description'],
            'discount_id' => $validated['discount_id'] ?? null,
        ];

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        if ($product->admin_id !== Auth::id()) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus produk ini');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
