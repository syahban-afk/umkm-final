<x-shop-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keranjang & Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Tab Navigation -->
            <div class="mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button onclick="showTab('cart')" id="cart-tab"
                            class="tab-button border-indigo-500 text-indigo-600 dark:text-indigo-400 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Keranjang Belanja
                        </button>
                        <button onclick="showTab('orders')" id="orders-tab"
                            class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Pesanan Saya
                        </button>
                        <button onclick="showTab('wishlist')" id="wishlist-tab"
                            class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Wishlist
                        </button>
                        <button onclick="showTab('reviews')" id="reviews-tab"
                            class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Review Produk
                        </button>
                    </nav>
                </div>
            </div>
            <!-- Cart Tab -->
            <div id="cart-content" class="tab-content">
                @if (isset($cartItems) && count($cartItems) > 0)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Produk dalam Keranjang</h3>
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
                                                Kuantitas</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Subtotal</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($cartItems as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex-shrink-0 h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
                                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                                            alt="{{ $item->product->name }}"
                                                            class="w-full h-15 object-cover rounded-md mb-4">
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
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    @if ($item->activeDiscount)
                                                        <div class="flex flex-col">
                                                            <span
                                                                class="text-gray-500 dark:text-gray-400 line-through">Rp
                                                                {{ number_format($item->price, 0, ',', '.') }}</span>
                                                            <span class="text-green-600 dark:text-green-400 font-medium">
                                                                Rp
                                                                {{ number_format($item->price * (1 - $item->activeDiscount->percentage / 100), 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                    @else
                                                        <span>Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                                    class="flex items-center" id="form-{{ $item->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button"
                                                        onclick="adjustQuantity({{ $item->id }}, -1, {{ $item->product->stock }})"
                                                        class="text-gray-500 dark:text-gray-400 focus:outline-none">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    </button>
                                                    <input type="number" id="quantity-{{ $item->id }}"
                                                        name="quantity" value="{{ $item->quantity }}" min="1"
                                                        max="{{ $item->product->stock }}"
                                                        class="mx-2 w-16 text-center rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    <button type="button"
                                                        onclick="adjustQuantity({{ $item->id }}, 1, {{ $item->product->stock }})"
                                                        class="text-gray-500 dark:text-gray-400 focus:outline-none">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                    </button>
                                                    <button type="submit"
                                                        class="ml-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                                        Update
                                                    </button>
                                                </form>

                                                <script>
                                                    function adjustQuantity(itemId, change, maxStock) {
                                                        const input = document.getElementById(`quantity-${itemId}`);
                                                        let currentValue = parseInt(input.value);
                                                        let newValue = currentValue + change;

                                                        // Validasi nilai
                                                        if (newValue < 1) newValue = 1;
                                                        if (newValue > maxStock) newValue = maxStock;

                                                        input.value = newValue;
                                                    }

                                                    // Validasi input manual
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        document.querySelectorAll('input[type="number"][name="quantity"]').forEach(input => {
                                                            input.addEventListener('change', function() {
                                                                const max = parseInt(this.getAttribute('max'));
                                                                let value = parseInt(this.value);

                                                                if (isNaN(value)) {
                                                                    this.value = 1;
                                                                } else if (value < 1) {
                                                                    this.value = 1;
                                                                } else if (value > max) {
                                                                    this.value = max;
                                                                }
                                                            });
                                                        });
                                                    });
                                                </script>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    @if ($item->activeDiscount)
                                                        <div class="flex flex-col">
                                                            <span
                                                                class="text-gray-500 dark:text-gray-400 line-through text-xs">
                                                                Rp
                                                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                            </span>
                                                            <span class="text-green-600 dark:text-green-400">
                                                                Rp
                                                                {{ number_format($item->price * (1 - $item->activeDiscount->percentage / 100) * $item->quantity, 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                    @else
                                                        <span>Rp
                                                            {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Pengiriman
                                </h3>
                                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <x-input-label for="name" :value="__('Nama Lengkap')" />
                                            <x-text-input id="name" class="block mt-1 w-full" type="text"
                                                name="name" :value="old('name', auth()->user()->name ?? '')" required autofocus />
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="phone" :value="__('Nomor Telepon')" />
                                            <x-text-input id="phone" class="block mt-1 w-full" type="text"
                                                name="phone" :value="old('phone')" required />
                                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                        </div>

                                        <div class="md:col-span-2">
                                            <x-input-label for="address" :value="__('Alamat Lengkap')" />
                                            <textarea id="address" name="address" rows="3"
                                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                required>{{ old('address') }}</textarea>
                                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="city" :value="__('Kota')" />
                                            <x-text-input id="city" class="block mt-1 w-full" type="text"
                                                name="city" :value="old('city')" required />
                                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="postal_code" :value="__('Kode Pos')" />
                                            <x-text-input id="postal_code" class="block mt-1 w-full" type="text"
                                                name="postal_code" :value="old('postal_code')" required />
                                            <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Metode
                                            Pembayaran</h3>
                                        <div class="space-y-4">
                                            <div class="flex items-center">
                                                <input id="payment_method_transfer" name="payment_method"
                                                    type="radio" value="transfer"
                                                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                                    checked>
                                                <label for="payment_method_transfer"
                                                    class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Transfer Bank
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="payment_method_cod" name="payment_method" type="radio"
                                                    value="cod"
                                                    class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="payment_method_cod"
                                                    class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Bayar di Tempat (COD)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ringkasan Pesanan
                                </h3>
                                <div class="space-y-4">

                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Total Belanjaan</span>
                                        <span class="text-gray-900 dark:text-white font-medium">
                                            Rp {{ number_format($subtotalBeforeDiscount, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Diskon</span>
                                        <span class="text-red-600 dark:text-red-400 font-medium">
                                            - Rp {{ number_format($totalDiscount, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                        <span class="text-gray-900 dark:text-white">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Biaya Pengiriman</span>
                                        <span class="text-gray-900 dark:text-white">
                                            Rp {{ number_format($shippingCost, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-between font-medium">
                                        <span class="text-gray-900 dark:text-white">Total</span>
                                        <span class="text-gray-900 dark:text-white">
                                            Rp {{ number_format($total, 0, ',', '.') }}
                                        </span>
                                    </div>

                                </div>
                                <div class="mt-6">
                                    <button type="submit" form="checkout-form"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        Proses Checkout
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Keranjang Kosong</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Keranjang belanja Anda masih kosong.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('shop.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Mulai Belanja
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Orders Tab -->
        <div id="orders-content" class="tab-content hidden">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Daftar Pesanan</h3>

                    @if (isset($orders) && !$orders->isEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID Pesanan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status Pesanan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status Pembayaran</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">#{{ $order->id }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white">{{ $order->order_date->format('d M Y') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($order->status === 'completed') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($order->payment->status === 'paid') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                                    @elseif($order->payment->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 @endif">
                                                    {{ ucfirst($order->payment->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Detail</a>

                                                @if($order->status === 'pending')
                                                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                                            Batalkan
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(isset($orders) && method_exists($orders, 'links'))
                            <div class="mt-4">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada pesanan</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum memiliki pesanan apapun.</p>
                            <div class="mt-6">
                                <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Mulai Belanja
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Wishlist Tab -->
        <div id="wishlist-content" class="tab-content hidden">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Wishlist Saya</h3>

                    @if (isset($wishlist) && !$wishlist->isEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($wishlist as $item)
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden">
                                    <div class="p-4">
                                        <div class="flex items-center justify-center h-48 bg-gray-100 dark:bg-gray-700 rounded-md mb-4">
                                            @if ($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="h-full object-contain">
                                            @else
                                                <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-1">{{ $item->product->name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $item->product->category->name }}</p>

                                        <div class="flex items-baseline mb-4">
                                            @if ($item->product->discount && $item->product->discount->is_active)
                                                <span class="text-lg font-bold text-gray-900 dark:text-white mr-2">
                                                    Rp {{ number_format($item->product->price - ($item->product->price * $item->product->discount->percentage / 100), 0, ',', '.') }}
                                                </span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400 line-through">
                                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                </span>
                                                <span class="ml-2 px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">
                                                    -{{ $item->product->discount->percentage }}%
                                                </span>
                                            @else
                                                <span class="text-lg font-bold text-gray-900 dark:text-white">
                                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex space-x-2">
                                            <a href="{{ route('shop.show', $item->product->id) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                Lihat
                                            </a>
                                            <form action="{{ route('wishlist.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(isset($wishlist) && method_exists($wishlist, 'links'))
                            <div class="mt-4">
                                {{ $wishlist->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Wishlist Kosong</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum memiliki produk di wishlist.</p>
                            <div class="mt-6">
                                <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Jelajahi Produk
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Review Produk Tab -->
        <div id="reviews-content" class="tab-content hidden">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Review Produk</h3>

                    @if (isset($reviewableProducts) && count($reviewableProducts) > 0)
                        <div class="space-y-6">
                            @foreach ($reviewableProducts as $product)
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden">
                                    <div class="p-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 h-16 w-16 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center mr-4">
                                                @if ($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover rounded-md">
                                                @else
                                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $product->name }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->category->name }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Dibeli pada: {{ $product->pivot->created_at->format('d M Y') }}</p>
                                            </div>
                                        </div>

                                        @php
                                            $review = $product->reviewByUser(auth()->id());
                                        @endphp

                                        @if ($review)
                                            <div class="mt-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                                                <div class="flex items-center mb-2">
                                                    <div class="flex">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <svg class="h-5 w-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $review->review_date->format('d M Y') }}</span>
                                                </div>
                                                <p class="text-gray-700 dark:text-gray-300">{{ $review->comment }}</p>
                                                <div class="mt-3">
                                                    <form action="{{ route('reviews.delete', $review->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                            Hapus Review
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @else
                                            <div class="mt-4">
                                                <form action="{{ route('reviews.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                                    <div class="mb-3">
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rating</label>
                                                        <div class="flex">
                                                            <div class="flex" id="rating-stars-{{ $product->id }}">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <button type="button" onclick="setRating({{ $product->id }}, {{ $i }})" class="rating-star text-gray-300 dark:text-gray-600 focus:outline-none">
                                                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                        </svg>
                                                                    </button>
                                                                @endfor
                                                            </div>
                                                            <input type="hidden" name="rating" id="rating-value-{{ $product->id }}" value="0">
                                                        </div>
                                                        @error('rating')
                                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="comment-{{ $product->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Komentar</label>
                                                        <textarea id="comment-{{ $product->id }}" name="comment" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required></textarea>
                                                        @error('comment')
                                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div>
                                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                            Kirim Review
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(isset($reviewableProducts) && method_exists($reviewableProducts, 'links'))
                            <div class="mt-4">
                                {{ $reviewableProducts->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada produk untuk direview</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum memiliki produk yang dapat direview.</p>
                            <div class="mt-6">
                                <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Jelajahi Produk
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <script>
            function showTab(tabName) {
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Show the selected tab content
                document.getElementById(tabName + '-content').classList.remove('hidden');

                // Update tab button styles
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.classList.remove('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
                    button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                });

                document.getElementById(tabName + '-tab').classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                document.getElementById(tabName + '-tab').classList.add('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
            }

            function setRating(productId, rating) {
                // Update hidden input value
                document.getElementById('rating-value-' + productId).value = rating;

                // Update star colors
                const stars = document.querySelectorAll('#rating-stars-' + productId + ' .rating-star');
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.remove('text-gray-300', 'dark:text-gray-600');
                        star.classList.add('text-yellow-400');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300', 'dark:text-gray-600');
                    }
                });
            }
        </script>
    </div>
</x-shop-layout>
