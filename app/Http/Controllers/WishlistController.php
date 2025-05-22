<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        // Ambil wishlist user yang sedang login
        $wishlists = Wishlist::where('customer_id', Auth::user()->customer->id)
            ->with('product')
            ->get();

        return view('shop.wishlist', compact('wishlists'));
    }

    public function add(Product $product)
    {
        // Cek apakah user sudah memiliki customer
        if (!Auth::user()->customer) {
            return redirect()->back()->with('error', 'Profil customer belum lengkap.');
        }

        // Cek apakah produk sudah ada di wishlist
        $exists = Wishlist::where('customer_id', Auth::user()->customer->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('info', 'Produk sudah ada di wishlist.');
        }

        // Tambahkan ke wishlist
        Wishlist::create([
            'customer_id' => Auth::user()->customer->id,
            'product_id' => $product->id
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke wishlist.');
    }

    public function remove($id)
    {
        // Hapus item dari wishlist
        $wishlist = Wishlist::where('customer_id', Auth::user()->customer->id)
            ->where('id', $id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return redirect()->route('wishlist.index')->with('success', 'Produk berhasil dihapus dari wishlist.');
        }

        return redirect()->route('wishlist.index')->with('error', 'Produk tidak ditemukan di wishlist.');
    }

    // API Methods (untuk keperluan admin atau API)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist = Wishlist::create($validated);
        return response()->json($wishlist, 201);
    }

    public function show(Wishlist $wishlist)
    {
        return response()->json($wishlist->load(['customer', 'product']));
    }

    public function update(Request $request, Wishlist $wishlist)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist->update($validated);
        return response()->json($wishlist);
    }

    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();
        return response()->json(null, 204);
    }
}
