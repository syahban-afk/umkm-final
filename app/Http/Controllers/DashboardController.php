<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            // Data untuk admin dashboard - filter berdasarkan admin yang login
            $totalProducts = Product::where('admin_id', Auth::id())->count();

            // Dapatkan order yang berkaitan dengan produk admin ini
            $productIds = Product::where('admin_id', Auth::id())->pluck('id');
            $orderIds = OrderDetail::whereIn('product_id', $productIds)->pluck('order_id')->unique();

            $totalOrders = Order::whereIn('id', $orderIds)->count();
            $pendingOrders = Order::whereIn('id', $orderIds)->where('status', 'pending')->count();
            $processingOrders = Order::whereIn('id', $orderIds)->where('status', 'processing')->count();
            $recentOrders = Order::with('customer')
                ->whereIn('id', $orderIds)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            return view('dashboard', compact(
                'totalProducts',
                'totalOrders',
                'pendingOrders',
                'processingOrders',
                'recentOrders'
            ));
        } else {
            // Data untuk customer dashboard
            // Cari customer_id berdasarkan user yang login
            $customer = Customer::where('email', Auth::user()->email)->first();

            // Ambil produk terbaru untuk ditampilkan di dashboard
            $latestProducts = Product::latest()->take(4)->get();

            if ($customer) {
                $customerOrders = Order::where('customer_id', $customer->id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();

                $totalCustomerOrders = Order::where('customer_id', $customer->id)->count();
                $lastOrder = Order::where('customer_id', $customer->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                return view('dashboard', compact(
                    'customerOrders',
                    'totalCustomerOrders',
                    'lastOrder',
                    'latestProducts'
                ));
            } else {
                // Jika customer belum memiliki data
                return view('dashboard', [
                    'customerOrders' => collect(),
                    'totalCustomerOrders' => 0,
                    'lastOrder' => null,
                    'latestProducts' => $latestProducts
                ]);
            }
        }
    }
}
