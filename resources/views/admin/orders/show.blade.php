<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Pesanan') }} #{{ $order->id }}
            </h2>
            <a href="{{ route('admin.orders.index') }}"
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
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Informasi Pesanan -->
                <div class="md:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Pesanan</h3>
                                <div class="flex space-x-2">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($order->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                        @if ($order->status == 'processing') bg-blue-100 text-blue-800 @endif
                                        @if ($order->status == 'completed') bg-green-100 text-green-800 @endif
                                        @if ($order->status == 'cancelled') bg-red-100 text-red-800 @endif
                                    ">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">ID Pesanan: <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $order->id }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Tanggal Pesanan: <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $order->order_date->format('d M Y H:i') }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Total: <span
                                            class="font-medium text-gray-900 dark:text-white">Rp
                                            {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Pelanggan: <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $order->customer->name }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Email: <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $order->customer->email }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Telepon: <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $order->customer->phone }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Form Update Status -->
                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST"
                                class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                @csrf
                                @method('PATCH')
                                <div class="flex items-center space-x-4">
                                    <label for="status"
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300">Last
                                        Status:</label>
                                    <select name="status" id="status"
                                        class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:ring focus:ring-indigo-500 {{ $order->status === 'completed' ? 'bg-gray-100 dark:bg-gray-700 cursor-not-allowed' : '' }}"
                                        {{ $order->status === 'completed' ? 'disabled' : '' }}>
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="processing"
                                            {{ $order->status === 'processing' ? 'selected' : '' }}>
                                            Processing
                                        </option>
                                        <option value="completed"
                                            {{ $order->status === 'completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>
                                    </select>
                                    @if ($order->status === 'completed')
                                        <button type="button"
                                            class="bg-gray-100 text-gray-400 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed"
                                            disabled>
                                            Completed
                                        </button>
                                    @else
                                        <button type="submit"
                                            class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-800">
                                            Update
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Detail Produk -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
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
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($order->orderDetails as $detail)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            @if ($detail->product && $detail->product->image)
                                                                <img class="h-10 w-10 rounded-full object-cover"
                                                                    src="{{ asset('storage/' . $detail->product->image) }}"
                                                                    alt="{{ $detail->product->name }}">
                                                            @else
                                                                <div
                                                                    class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                                    <svg class="h-6 w-6 text-gray-400" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="ml-4">
                                                            <div
                                                                class="text-sm font-medium text-gray-900 dark:text-white">
                                                                {{ $detail->product->name ?? 'Produk tidak tersedia' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    Rp {{ number_format($detail->price, 0, ',', '.') }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $detail->quantity }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    Rp
                                                    {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="md:col-span-1 space-y-6">
                    <!-- Informasi Pembayaran -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Pembayaran</h3>
                            @if ($order->payment)
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Metode: <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $order->payment->payment_method }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Status:
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if ($order->payment->status == 'paid') bg-green-100 text-green-800 @endif
                                            @if ($order->payment->status == 'unpaid') bg-yellow-100 text-yellow-800 @endif
                                            @if ($order->payment->status == 'cancelled') bg-red-100 text-red-800 @endif
                                        ">
                                            {{ ucfirst($order->payment->status) }}
                                        </span>
                                    </p>
                                    @if ($order->payment->payment_date)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Tanggal Pembayaran: <span
                                                class="font-medium text-gray-900 dark:text-white">{{ $order->payment->payment_date->format('d M Y H:i') }}</span>
                                        </p>
                                    @endif
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Jumlah: <span
                                            class="font-medium text-gray-900 dark:text-white">Rp
                                            {{ number_format($order->payment->amount, 0, ',', '.') }}</span></p>
                                </div>
                            @else
                                <p class="text-sm text-gray-600 dark:text-gray-400">Tidak ada informasi pembayaran.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Pengiriman -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Pengiriman
                            </h3>
                            @if ($order->delivery)
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Kurir: <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $order->delivery->courier_name }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">No. Resi: <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $order->delivery->tracking_number }}</span>
                                    </p>
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
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Tanggal Pengiriman: <span
                                                class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($order->delivery->delivery_date)->format('d M Y') }}
                                            </span></p>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('admin.deliveries.edit', $order) }}"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        Update Pengiriman
                                    </a>
                                </div>
                            @else
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Belum ada informasi
                                    pengiriman.</p>
                                <a href="{{ route('admin.deliveries.edit', $order) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Tambah Pengiriman
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Alamat Pengiriman -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Alamat Pengiriman</h3>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->customer->address }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->customer->city }},
                                    {{ $order->customer->postal_code }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->customer->province }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
