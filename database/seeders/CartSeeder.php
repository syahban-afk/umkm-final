<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua customer
        $customers = Customer::all();

        // Ambil semua produk
        $products = Product::all();

        // Jika tidak ada customer atau produk, keluar dari seeder
        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        // Untuk setiap customer, tambahkan 1-3 produk ke keranjang
        foreach ($customers as $customer) {
            $randomProducts = $products->random(rand(1, 3));

            foreach ($randomProducts as $product) {
                Cart::create([
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 5),
                    'price' => $product->price
                ]);
            }
        }
    }
}
