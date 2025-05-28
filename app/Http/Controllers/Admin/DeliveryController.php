<?php

namespace App\Http\Controllers\Admin;

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
