<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CartController extends Controller
{
    public function index()
    {
        // Ambil item keranjang dari session
        $cartItems = session('cart', []);
        $subtotal = 0;

        // Hitung subtotal
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Biaya pengiriman (bisa disesuaikan dengan logika bisnis)
        $shippingCost = 10000; // Rp 10.000

        // Total keseluruhan
        $total = $subtotal + $shippingCost;

        return view('shop.cart', compact('cartItems', 'subtotal', 'shippingCost', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        // Validasi stok
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // Cek apakah produk sudah ada di keranjang
        if (isset($cart[$product->id])) {
            // Update quantity jika sudah ada
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            // Tambahkan produk baru ke keranjang
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'product' => $product
            ];
        }

        // Simpan keranjang ke session
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, $id)
    {
        $quantity = $request->input('quantity');

        // Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // Update quantity jika produk ada di keranjang
        if (isset($cart[$id])) {
            // Validasi stok
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
        // Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // Hapus produk dari keranjang jika ada
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function checkout(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'payment_method' => 'required|string'
        ]);

        // Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // Cek apakah keranjang kosong
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        try {
            DB::beginTransaction();

            // Cek atau buat data customer
            $customer = Auth::user()->customer;
            if (!$customer) {
                // Buat customer baru jika belum ada
                $customer = Customer::create([
                    'user_id' => Auth::id(),
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'] . ', ' . $validated['city'] . ' ' . $validated['postal_code']
                ]);
            }

            // Hitung total
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            // Biaya pengiriman
            $shippingCost = 10000; // Rp 10.000

            // Total keseluruhan
            $total = $subtotal + $shippingCost;

            // Buat order baru
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_date' => Carbon::now(),
                'status' => 'pending',
                'total_amount' => $total
            ]);

            // Buat detail order
            foreach ($cart as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                // Kurangi stok produk
                $product = Product::find($item['id']);
                $product->stock -= $item['quantity'];
                $product->save();
            }

            // Buat data pengiriman
            $order->delivery()->create([
                'status' => 'pending',
                'shipping_cost' => $shippingCost,
                'address' => $validated['address'] . ', ' . $validated['city'] . ' ' . $validated['postal_code']
            ]);

            // Buat data pembayaran
            $order->payment()->create([
                'payment_method' => $validated['payment_method'],
                'amount' => $total,
                'status' => 'pending'
            ]);

            DB::commit();

            // Kosongkan keranjang
            session()->forget('cart');

            return redirect()->route('orders.payment', $order->id)->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
