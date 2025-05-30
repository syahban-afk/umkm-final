<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     */
    public function index()
    {
        // Cek apakah user sudah memiliki customer
        $customer = $this->getOrCreateCustomer();
        if (!$customer) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        // Ambil item keranjang dari database dengan relasi diskon aktif
        $cartItems = Cart::where('customer_id', $customer->id)
            ->with(['product.category', 'product.discounts' => function ($query) {
                $query->active(); // Pastikan ada scope active() di model Discount
            }])
            ->get()
            ->each(function ($item) {
                $item->activeDiscount = optional($item->product->discounts)->first();
            });

        // Hitung subtotal dan diskon
        $subtotalBeforeDiscount = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $totalDiscount = $cartItems->sum(function ($item) {
            if ($item->activeDiscount) {
                return ($item->price * $item->activeDiscount->percentage / 100) * $item->quantity;
            }
            return 0;
        });

        $subtotalAfterDiscount = $subtotalBeforeDiscount - $totalDiscount;
        $shippingCost = 10000; // Rp 10.000
        $total = $subtotalAfterDiscount + $shippingCost;

        return view('shop.cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotalAfterDiscount,
            'subtotalBeforeDiscount' => $subtotalBeforeDiscount,
            'totalDiscount' => $totalDiscount,
            'shippingCost' => $shippingCost,
            'total' => $total
        ]);
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        // Validasi stok
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // Cek apakah user sudah memiliki customer
        $customer = $this->getOrCreateCustomer();
        if (!$customer) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        // Cek apakah produk sudah ada di keranjang
        $cartItem = Cart::where('customer_id', $customer->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Update quantity jika produk sudah ada
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Tambahkan produk baru ke keranjang
            Cart::create([
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, $id)
    {
        $cartItem = Cart::findOrFail($id);
        $quantity = $request->input('quantity');

        // Validasi stok
        $product = Product::find($cartItem->product_id);
        if ($product && $product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // Update quantity
        $cartItem->quantity = $quantity;
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
    }

    /**
     * Remove a cart item.
     */
    public function remove($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    /**
     * Process checkout from cart.
     */
    public function checkout(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'payment_method' => 'required|in:transfer,cod',
        ]);

        // Cek apakah user sudah memiliki customer
        $customer = $this->getOrCreateCustomer();
        if (!$customer) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        // Update informasi customer
        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address . ', ' . $request->city . ' ' . $request->postal_code,
        ]);

        // Ambil item keranjang
        $cartItems = Cart::where('customer_id', $customer->id)
            ->with(['product.category', 'product.discounts' => function ($query) {
                $query->active();
            }])
            ->has('product')
            ->get()
            ->each(function ($item) {
                $item->activeDiscount = optional($item->product?->discounts)->first();
            });

        // Cek jika keranjang kosong
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Hitung subtotal dan diskon
        $subtotalBeforeDiscount = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $totalDiscount = $cartItems->sum(function ($item) {
            if ($item->activeDiscount) {
                return ($item->price * $item->activeDiscount->percentage / 100) * $item->quantity;
            }
            return 0;
        });

        $subtotalAfterDiscount = $subtotalBeforeDiscount - $totalDiscount;
        $shippingCost = 10000; // Rp 10.000
        $total = $subtotalAfterDiscount + $shippingCost;

        // Mulai transaksi database
        try {
            DB::beginTransaction();

            // Buat order baru
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_date' => now(),
                'status' => 'pending',
                'total_amount' => $total,
            ]);

            // Buat order details
            foreach ($cartItems as $item) {
                // Hitung harga setelah diskon
                $priceAfterDiscount = $item->price;
                if ($item->activeDiscount) {
                    $priceAfterDiscount = $item->price * (1 - $item->activeDiscount->percentage / 100);
                }

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $priceAfterDiscount,
                ]);

                // Kurangi stok produk
                $product = Product::find($item->product_id);
                $product->stock -= $item->quantity;
                $product->save();
            }

            // Buat payment
            Payment::create([
                'order_id' => $order->id,
                'payment_date' => now(),
                'amount' => $total,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method === 'cod' ? 'pending' : 'unpaid',
            ]);

            // Hapus item keranjang
            Cart::where('customer_id', $customer->id)->delete();

            DB::commit();

            // Redirect ke halaman sukses checkout
            return redirect()->route('checkout.success', ['order' => $order->id])
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')
                ->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Display checkout success page.
     */
    public function checkoutSuccess(Order $order)
    {
        // Pastikan user hanya bisa melihat pesanan miliknya sendiri
        $customer = $this->getOrCreateCustomer();
        if (!$customer || $order->customer_id !== $customer->id) {
            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Load relasi yang diperlukan
        $order->load(['customer', 'orderDetails.product', 'payment']);

        return view('checkout.success', compact('order'));
    }

    /**
     * Get or create customer for the authenticated user.
     */
    private function getOrCreateCustomer()
    {
        $user = Auth::user();

        // Cek apakah user sudah memiliki customer
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer && $user->email) {
            // Buat customer baru jika belum ada
            $customer = Customer::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => '',  // Perlu diisi oleh user
                'address' => '' // Perlu diisi oleh user
            ]);
        }

        return $customer;
    }
}
