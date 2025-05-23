<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shippingCost = 10000; // Rp 10.000
        $total = $subtotal + $shippingCost;

        return view('shop.cart', compact('cartItems', 'subtotal', 'shippingCost', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'product' => $product
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity');

        if (isset($cart[$id])) {
            $product = Product::find($id);
            if ($product && $product->stock < $quantity) {
                return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
            }
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}