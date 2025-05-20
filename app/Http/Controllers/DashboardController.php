<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            // Data untuk admin dashboard
            $totalProducts = Product::count();
            $totalOrders = Order::count();
            $pendingOrders = Order::where('status', 'pending')->count();
            $processingOrders = Order::where('status', 'processing')->count();
            $recentOrders = Order::with('customer')
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
                    'lastOrder'
                ));
            } else {
                // Jika customer belum memiliki data
                return view('dashboard', [
                    'customerOrders' => collect(),
                    'totalCustomerOrders' => 0,
                    'lastOrder' => null
                ]);
            }
        }
    }
}
