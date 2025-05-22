<x-shop-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(isset($cartItems) && count($cartItems) > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Produk</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kuantitas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subtotal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->product->category->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" onclick="decrementQuantity('quantity-{{ $item->id }}')" class="text-gray-500 dark:text-gray-400 focus:outline-none">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    </button>
                                                    <input type="number" id="quantity-{{ $item->id }}" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="mx-2 w-16 text-center rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    <button type="button" onclick="incrementQuantity('quantity-{{ $item->id }}', {{ $item->product->stock }})" class="text-gray-500 dark:text-gray-400 focus:outline-none">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                    </button>
                                                    <button type="submit" class="ml-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                                        Update
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
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
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Pengiriman</h3>
                                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <x-input-label for="name" :value="__('Nama Lengkap')" />
                                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', auth()->user()->name ?? '')" required autofocus />
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="phone" :value="__('Nomor Telepon')" />
                                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
                                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                        </div>

                                        <div class="md:col-span-2">
                                            <x-input-label for="address" :value="__('Alamat Lengkap')" />
                                            <textarea id="address" name="address" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>{{ old('address') }}</textarea>
                                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="city" :value="__('Kota')" />
                                            <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required />
                                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="postal_code" :value="__('Kode Pos')" />
                                            <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code')" required />
                                            <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Metode Pembayaran</h3>
                                        <div class="space-y-4">
                                            <div class="flex items-center">
                                                <input id="payment_method_transfer" name="payment_method" type="radio" value="transfer" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600" checked>
                                                <label for="payment_method_transfer" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Transfer Bank
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="payment_method_cod" name="payment_method" type="radio" value="cod" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="payment_method_cod" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
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
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ringkasan Pesanan</h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                        <span class="text-gray-900 dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Biaya Pengiriman</span>
                                        <span class="text-gray-900 dark:text-white">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-between font-medium">
                                        <span class="text-gray-900 dark:text-white">Total</span>
                                        <span class="text-gray-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <button type="submit" form="checkout-form" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
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
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Keranjang Kosong</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Keranjang belanja Anda masih kosong.</p>
                        <div class="mt-6">
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Mulai Belanja
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function incrementQuantity(id, max) {
            const input = document.getElementById(id);
            const currentValue = parseInt(input.value);
            if (currentValue < max) {
                input.value = currentValue + 1;
            }
        }

        function decrementQuantity(id) {
            const input = document.getElementById(id);
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }
    </script>
    @endpush
</x-shop-layout>
