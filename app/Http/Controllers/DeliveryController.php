<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function index()
    {
        // Dapatkan ID produk milik admin yang login
        $productIds = Product::where('admin_id', Auth::id())->pluck('id');

        // Dapatkan ID order yang berisi produk milik admin
        $orderIds = OrderDetail::whereIn('product_id', $productIds)->pluck('order_id')->unique();

        $deliveries = Delivery::with(['order', 'order.customer'])
            ->whereIn('order_id', $orderIds)
            ->latest()
            ->paginate(10);

        return view('admin.deliveries.index', compact('deliveries'));
    }

    public function edit(Order $order)
    {
        // Verifikasi bahwa order ini berisi produk milik admin yang login
        $productIds = Product::where('admin_id', Auth::id())->pluck('id');
        $orderContainsAdminProducts = OrderDetail::where('order_id', $order->id)
            ->whereIn('product_id', $productIds)
            ->exists();

        if (!$orderContainsAdminProducts) {
            return redirect()->route('admin.deliveries.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit pengiriman ini');
        }

        $delivery = $order->delivery ?? new Delivery();
        return view('admin.deliveries.edit', compact('order', 'delivery'));
    }

    public function update(Request $request, Order $order)
    {
        // Verifikasi bahwa order ini berisi produk milik admin yang login
        $productIds = Product::where('admin_id', Auth::id())->pluck('id');
        $orderContainsAdminProducts = OrderDetail::where('order_id', $order->id)
            ->whereIn('product_id', $productIds)
            ->exists();

        if (!$orderContainsAdminProducts) {
            return redirect()->route('admin.deliveries.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate pengiriman ini');
        }

        $request->validate([
            'courier_name' => 'required|string|max:100',
            'tracking_number' => 'required|string|max:100',
            'status' => 'required|in:pending,in_transit,delivered',
        ]);

        $delivery = $order->delivery ?? new Delivery();
        $oldStatus = $delivery->status ?? null;
        $newStatus = $request->status;

        $delivery->order_id = $order->id;
        $delivery->courier_name = $request->courier_name;
        $delivery->tracking_number = $request->tracking_number;
        $delivery->status = $newStatus;

        if ($newStatus == 'in_transit' && $oldStatus != 'in_transit') {
            $delivery->delivery_date = now();
        }

        $delivery->save();

        if ($newStatus == 'delivered' && $order->status != 'completed') {
            $order->status = 'completed';
            $order->save();
        } elseif ($newStatus == 'in_transit' && $order->status == 'pending') {
            $order->status = 'processing';
            $order->save();
        }

        return redirect()->route('admin.deliveries.index')->with('success', 'Status pengiriman berhasil diperbarui');
    }
}
