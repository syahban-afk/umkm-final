<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::with(['order', 'order.customer'])->latest()->paginate(10);
        return view('admin.deliveries.index', compact('deliveries'));
    }

    public function edit(Order $order)
    {
        $delivery = $order->delivery ?? new Delivery();
        return view('admin.deliveries.edit', compact('order', 'delivery'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'courier_name' => 'required|string|max:100',
            'tracking_number' => 'required|string|max:100',
            'status' => 'required|in:pending,in_transit,delivered',
        ]);

        $delivery = $order->delivery ?? new Delivery();
        $delivery->order_id = $order->id;
        $delivery->courier_name = $request->courier_name;
        $delivery->tracking_number = $request->tracking_number;
        $delivery->status = $request->status;

        // Update delivery_date jika status berubah
        if ($request->status == 'in_transit' && $delivery->status != 'in_transit') {
            $delivery->delivery_date = now();
        }

        $delivery->save();

        // Update order status jika diperlukan
        if ($request->status == 'delivered' && $order->status == 'processing') {
            $order->status = 'completed';
            $order->save();
        }

        return redirect()->route('admin.deliveries.index')->with('success', 'Status pengiriman berhasil diperbarui');
    }
}
