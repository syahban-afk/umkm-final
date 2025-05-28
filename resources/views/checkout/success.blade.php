<x-shop-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Checkout Berhasil') }}
            </h2>
            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali Belanja
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Status Pesanan -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                        <div class="mb-4 md:mb-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Pesanan #{{ $order->id }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Tanggal: {{ $order->order_date->format('d M Y H:i') }}</p>
                        </div>
                        <div class="flex items-center">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                @if($order->status === 'completed') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Detail Pesanan -->
                <div class="md:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Detail Pesanan</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Produk</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($order->orderDetails as $detail)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
                                                            <img src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product->name }}" class="w-full h-full object-cover rounded-md">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $detail->product->name }}</div>
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $detail->product->category->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white">Rp {{ number_format($detail->price, 0, ',', '.') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white">{{ $detail->quantity }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pembayaran dan Pengiriman -->
                <div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ringkasan Pesanan</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                    <span class="text-gray-900 dark:text-white">Rp {{ number_format($order->total_amount - 10000, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Biaya Pengiriman</span>
                                    <span class="text-gray-900 dark:text-white">Rp 10.000</span>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-between font-medium">
                                    <span class="text-gray-900 dark:text-white">Total</span>
                                    <span class="text-gray-900 dark:text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Pembayaran</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Metode Pembayaran</span>
                                    <span class="text-gray-900 dark:text-white">
                                        {{ $order->payment->payment_method === 'transfer' ? 'Transfer Bank' : 'Bayar di Tempat (COD)' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Status Pembayaran</span>
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($order->payment->status === 'paid') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                        @elseif($order->payment->status === 'unpaid') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 @endif">
                                        {{ ucfirst($order->payment->status) }}
                                    </span>
                                </div>
                            </div>

                            @if($order->payment->payment_method === 'transfer' && $order->payment->status !== 'paid')
                                <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Instruksi Pembayaran:</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Silakan transfer ke rekening berikut:</p>
                                    <div class="space-y-1">
                                        <p class="text-sm text-gray-900 dark:text-white">Bank BCA</p>
                                        <p class="text-sm text-gray-900 dark:text-white">No. Rekening: 1234567890</p>
                                        <p class="text-sm text-gray-900 dark:text-white">Atas Nama: UMKM Marketplace</p>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Jumlah: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Pengiriman</h3>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400 block">Nama Penerima:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $order->customer->name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400 block">Alamat Pengiriman:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $order->customer->address }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400 block">Telepon:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $order->customer->phone }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Lihat Semua Pesanan
                </a>
            </div>
        </div>
    </div>
</x-shop-layout>
