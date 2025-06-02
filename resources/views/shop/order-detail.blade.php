<x-shop-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Pesanan') }} #{{ $order->id }}
            </h2>
            <a href="{{ route('cart.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Pesanan -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                        <div class="mb-4 md:mb-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Status Pesanan</h3>
                            <div class="mt-2 flex items-center">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($order->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                    @if ($order->status == 'processing') bg-blue-100 text-blue-800 @endif
                                    @if ($order->status == 'completed') bg-green-100 text-green-800 @endif
                                    @if ($order->status == 'cancelled') bg-red-100 text-red-800 @endif
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">Tanggal Pesanan:
                                    {{ $order->order_date->format('d M Y') }}</span>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Status Pembayaran</h3>
                            <div class="mt-2 flex items-center">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if (!$order->payment || $order->payment->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                    @if ($order->payment && $order->payment->status == 'paid') bg-green-100 text-green-800 @endif
                                    @if ($order->payment && $order->payment->status == 'failed') bg-red-100 text-red-800 @endif
                                ">
                                    {{ $order->payment ? ucfirst($order->payment->status) : 'Menunggu Pembayaran' }}
                                </span>
                                @if ($order->payment)
                                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">Metode:
                                        {{ ucfirst($order->payment->payment_method) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Produk -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Detail Produk</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Produk</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Harga</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($order->orderDetails as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $item->product->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $item->product->category->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">Rp
                                                {{ number_format($item->price, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $item->quantity }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">Rp
                                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        Subtotal:</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        Rp
                                        {{ number_format($order->total_amount - ($order->delivery ? $order->delivery->shipping_cost : 0), 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        Biaya Pengiriman:</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        Rp
                                        {{ number_format($order->delivery ? $order->delivery->shipping_cost : 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        Total:</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Informasi Pengiriman -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Pengiriman</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat Pengiriman</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $order->customer->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->customer->phone }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->customer->address }}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Pengiriman</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                @if ($order->delivery)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Kurir: <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $order->delivery->courier_name }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">No. Resi: <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $order->delivery->tracking_number }}</span>
                                    </p>
                                    <!-- Ganti bagian status pengiriman -->
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Status:
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if ($order->delivery->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                            @if ($order->delivery->status == 'in_transit') bg-blue-100 text-blue-800 @endif
                                            @if ($order->delivery->status == 'delivered') bg-green-100 text-green-800 @endif
                                        ">
                                            {{ ucfirst(str_replace('_', ' ', $order->delivery->status)) }}
                                        </span>
                                    </p>
                                    @if ($order->delivery->delivery_date)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Tanggal Pengiriman:
                                            <span
                                                class="font-medium text-gray-900 dark:text-white">{{ $order->delivery->delivery_date->format('d M Y') }}</span>
                                        </p>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Informasi pengiriman belum
                                        tersedia.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end space-x-4">
                @if (($order->payment && $order->payment->status == 'pending') || !$order->payment)
                    <a href="{{ route('orders.payment', $order->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Upload Bukti Pembayaran
                    </a>
                @endif

                @if ($order->status == 'pending')
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                            Batalkan Pesanan
                        </button>
                    </form>
                @endif

                @if ($order->status == 'completed')
                    <a href="{{ route('reviews.create', ['order_id' => $order->id]) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Beri Ulasan
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-shop-layout>
