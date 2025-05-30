<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>

            @if (auth()->user()->role === 'customer')
                <form action="{{ route('become.admin') }}" method="POST" class="mt-4 md:mt-0">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Buka Toko Sekarang
                    </button>
                </form>
            @endif
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if (auth()->user()->role === 'admin')
                <!-- Admin Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition duration-300 hover:shadow-md">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Total Produk') }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                                </svg>
                            </div>
                            <p class="text-3xl font-bold mt-3 text-gray-900 dark:text-gray-100">
                                {{ $totalProducts ?? 0 }}</p>
                        </div>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition duration-300 hover:shadow-md">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Total Pesanan') }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p class="text-3xl font-bold mt-3 text-gray-900 dark:text-gray-100">{{ $totalOrders ?? 0 }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition duration-300 hover:shadow-md">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Pesanan Menunggu') }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-3xl font-bold mt-3 text-gray-900 dark:text-gray-100">
                                {{ $pendingOrders ?? 0 }}</p>
                        </div>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition duration-300 hover:shadow-md">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Pesanan Diproses') }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <p class="text-3xl font-bold mt-3 text-gray-900 dark:text-gray-100">
                                {{ $processingOrders ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition duration-300 hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Pesanan Terbaru') }}</h3>
                            <a href="{{ route('admin.orders.index') }}"
                                class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Lihat Semua</a>
                        </div>
                        <div class="overflow-x-auto rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            ID</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Pelanggan</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($recentOrders ?? [] as $order)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $order->id }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $order->customer->name }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $order->order_date }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if ($order->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                            @if ($order->status == 'processing') bg-blue-100 text-blue-800 @endif
                                            @if ($order->status == 'completed') bg-green-100 text-green-800 @endif
                                            @if ($order->status == 'cancelled') bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5"
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                Tidak ada pesanan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <!-- Customer Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition duration-300 hover:shadow-md">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Total Pesanan Anda') }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p class="text-3xl font-bold mt-3 text-gray-900 dark:text-gray-100">
                                {{ $totalCustomerOrders ?? 0 }}</p>
                        </div>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition duration-300 hover:shadow-md">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Status Pesanan Terakhir') }}</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            @if (isset($lastOrder) && $lastOrder)
                                <span
                                    class="mt-3 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if ($lastOrder->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                            @if ($lastOrder->status == 'processing') bg-blue-100 text-blue-800 @endif
                            @if ($lastOrder->status == 'completed') bg-green-100 text-green-800 @endif
                            @if ($lastOrder->status == 'cancelled') bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($lastOrder->status) }}
                                </span>
                            @else
                                <p class="text-sm mt-3 text-gray-500 dark:text-gray-400">{{ __('Belum ada pesanan') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Customer's Recent Orders -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition duration-300 hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Pesanan Terbaru Anda') }}</h3>
                        </div>
                        <div class="overflow-x-auto rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            ID</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($customerOrders ?? [] as $order)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $loop->iteration }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $order->order_date->format('d M Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if ($order->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                            @if ($order->status == 'processing') bg-blue-100 text-blue-800 @endif
                                            @if ($order->status == 'completed') bg-green-100 text-green-800 @endif
                                            @if ($order->status == 'cancelled') bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                Anda belum memiliki pesanan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Bagian Tampilan Shop -->
                <div
                    class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition duration-300 hover:shadow-md">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            {{ __('Produk Terbaru di Marketplace') }}</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @forelse(\App\Models\Product::latest()->take(4)->get() as $product)
                                <div
                                    class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition duration-300 hover:shadow-lg">
                                    <div class="h-48 overflow-hidden">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 truncate">
                                            {{ $product->name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 truncate">
                                            {{ $product->description }}</p>
                                        <div class="mt-2 flex justify-between items-center">
                                            <span class="text-lg font-bold text-gray-900 dark:text-gray-100">Rp
                                                {{ number_format($product->price, 0, ',', '.') }}</span>
                                            <a href="{{ route('shop.show', $product->id) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium">Lihat
                                                Detail</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-8">
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada produk tersedia</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-6 text-center">
                            <a href="{{ route('shop.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Lihat Semua Produk') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
