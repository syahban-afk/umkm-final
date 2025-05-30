<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use App\Models\Order;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        // Cek apakah user sudah memiliki customer
        if (!Auth::user()->customer) {
            return redirect()->route('profile.edit')->with('error', 'Profil customer belum lengkap.');
        }

        // Ambil wishlist user yang sedang login
        $wishlists = Wishlist::where('customer_id', Auth::user()->customer->id)
            ->with('product.category')
            ->get();

        // Ambil produk yang telah dibeli oleh user
        $purchasedProducts = collect();

        // Ambil semua order milik customer
        $orders = Order::where('customer_id', Auth::user()->customer->id)
            ->where('status', 'completed')
            ->with('orderDetails.product.category')
            ->get();

        // Kumpulkan semua produk yang telah dibeli
        foreach ($orders as $order) {
            foreach ($order->orderDetails as $detail) {
                // Tambahkan informasi tanggal pembelian ke produk
                $product = $detail->product;
                $product->pivot = (object)[
                    'created_at' => $order->order_date
                ];

                // Cek apakah produk sudah memiliki review dari user ini
                $product->userReview = $product->reviewByUser(Auth::id());

                // Tambahkan ke koleksi jika belum ada
                if (!$purchasedProducts->contains('id', $product->id)) {
                    $purchasedProducts->push($product);
                }
            }
        }

        return view('shop.wishlist', compact('wishlists', 'purchasedProducts'));
    }

    public function add(Product $product)
    {
        try {
            // Cek apakah user sudah memiliki customer profile
            if (!Auth::user()->customer) {
                return redirect()->back()->with('error', 'Anda perlu melengkapi profil customer terlebih dahulu.');
            }

            // Cek apakah produk sudah ada di wishlist
            $wishlist = Wishlist::where('customer_id', Auth::user()->customer->id)
                ->where('product_id', $product->id)
                ->first();

            // Jika sudah ada, hapus dari wishlist
            if ($wishlist) {
                $wishlist->delete();
                return redirect()->back()->with('success', 'Produk berhasil dihapus dari wishlist.');
            }

            // Jika belum ada, tambahkan ke wishlist
            Wishlist::create([
                'customer_id' => Auth::user()->customer->id,
                'product_id' => $product->id
            ]);

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke wishlist.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses wishlist.');
        }
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
