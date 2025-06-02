<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders for admin.
     */
    public function index()
    {
        // Dapatkan ID produk milik admin yang login
        $productIds = Product::where('admin_id', Auth::id())->pluck('id');

        // Dapatkan ID order yang berisi produk milik admin
        $orderIds = OrderDetail::whereIn('product_id', $productIds)->pluck('order_id')->unique();

        $orders = Order::with(['customer', 'payment'])
            ->whereIn('id', $orderIds)
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the details of a specific order for admin.
     */
    public function show(Order $order)
    {
        // Verifikasi bahwa order ini berisi produk milik admin yang login
        $productIds = Product::where('admin_id', Auth::id())->pluck('id');
        $orderContainsAdminProducts = OrderDetail::where('order_id', $order->id)
            ->whereIn('product_id', $productIds)
            ->exists();

        if (!$orderContainsAdminProducts) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat pesanan ini');
        }

        $order->load(['orderDetails.product', 'payment', 'customer', 'delivery']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the status of an order.
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Verifikasi bahwa order ini berisi produk milik admin yang login
        $productIds = Product::where('admin_id', Auth::id())->pluck('id');
        $orderContainsAdminProducts = OrderDetail::where('order_id', $order->id)
            ->whereIn('product_id', $productIds)
            ->exists();

        if (!$orderContainsAdminProducts) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengubah status pesanan ini');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus
        ]);

        if ($oldStatus !== 'processing' && $newStatus === 'processing' && !$order->delivery) {
            $delivery = new Delivery();
            $delivery->order_id = $order->id;
            $delivery->status = 'pending';
            $delivery->delivery_date = now();
            $delivery->courier_name = 'Belum ditentukan';
            $delivery->tracking_number = 'Belum tersedia';
            $delivery->save();
        }

        if ($newStatus === 'completed' && $order->delivery && $order->delivery->status !== 'delivered') {
            $order->delivery->update([
                'status' => 'delivered',
                'delivery_date' => now()
            ]);
        }

        if ($newStatus === 'completed' && $order->payment && $order->payment->status !== 'paid') {
            $order->payment->update([
                'status' => 'paid',
                'payment_date' => now()
            ]);
        }

        if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
            if ($order->payment && $order->payment->status === 'unpaid') {
                $order->payment->update([
                    'status' => 'cancelled'
                ]);
            }

            foreach ($order->orderDetails as $detail) {
                $product = $detail->product;
                $product->stock += $detail->quantity;
                $product->save();
            }
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
