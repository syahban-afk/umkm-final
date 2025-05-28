<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $orders = Order::where('customer_id', $customer->id)
            ->with(['payment'])
            ->latest()
            ->paginate(10);

        // Ambil item keranjang dari database dengan relasi diskon aktif
        $cartItems = Cart::where('customer_id', $customer->id)
            ->with(['product.category', 'product.discounts' => function ($query) {
                $query->active(); // Pastikan ada scope active() di model Discount
            }])
            ->get()
            ->each(function ($item) {
                $item->activeDiscount = $item->product->discounts->first();
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

        return view('shop.orders.index', [
            'orders' => $orders,
            'cartItems' => $cartItems,
            'subtotalBeforeDiscount' => $subtotalBeforeDiscount,
            'totalDiscount' => $totalDiscount,
            'subtotal' => $subtotalAfterDiscount,
            'shippingCost' => $shippingCost,
            'total' => $total,
        ]);
    }

    /**
     * Show the details of a specific order.
     */
    public function show(Order $order)
    {
        $customer = $this->getCustomer();
        if (!$customer || $order->customer_id !== $customer->id) {
            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $order->load(['orderDetails.product', 'payment']);

        // Optional: Ambil cart juga untuk tampilkan subtotal/diskon
        $cartItems = Cart::where('customer_id', $customer->id)
            ->with(['product.category', 'product.discounts' => function ($query) {
                $query->active();
            }])
            ->get()
            ->each(function ($item) {
                // Jika tidak ada relasi discounts, tidak error
                $item->activeDiscount = optional($item->product->discounts)->first();
            });

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
        $shippingCost = 10000;
        $total = $subtotalAfterDiscount + $shippingCost;

        return view('shop.orders.show', [
            'order' => $order,
            'cartItems' => $cartItems,
            'subtotalBeforeDiscount' => $subtotalBeforeDiscount,
            'totalDiscount' => $totalDiscount,
            'subtotal' => $subtotalAfterDiscount,
            'shippingCost' => $shippingCost,
            'total' => $total,
        ]);
    }


    /**
     * Cancel an order.
     */
    public function cancel(Order $order)
    {
        $customer = $this->getCustomer();
        if (!$customer || $order->customer_id !== $customer->id) {
            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Hanya pesanan dengan status pending yang bisa dibatalkan
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Hanya pesanan dengan status pending yang dapat dibatalkan.');
        }

        $order->update([
            'status' => 'cancelled'
        ]);

        // Update status pembayaran jika belum dibayar
        if ($order->payment && $order->payment->status === 'unpaid') {
            $order->payment->update([
                'status' => 'cancelled'
            ]);
        }

        // Kembalikan stok produk
        foreach ($order->orderDetails as $detail) {
            $product = $detail->product;
            $product->stock += $detail->quantity;
            $product->save();
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Get customer for the authenticated user.
     */
    private function getCustomer()
    {
        $user = Auth::user();
        return Customer::where('user_id', $user->id)->first();
    }
}
