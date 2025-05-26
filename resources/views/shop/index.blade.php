<x-shop-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Produk Kami') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form action="{{ route('shop.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Pencarian berdasarkan nama -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Cari Produk
                                </label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            <!-- Filter berdasarkan kategori -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Kategori
                                </label>
                                <select name="category" id="category"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter berdasarkan diskon -->
                            <div>
                                <label for="discount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Diskon
                                </label>
                                <select name="discount" id="discount"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Semua Produk</option>
                                    <option value="active" {{ request('discount') == 'active' ? 'selected' : '' }}>
                                        Diskon Aktif
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                        <div class="p-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-48 object-cover rounded-md mb-4">
                            @else
                                <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 rounded-md mb-4 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <div class="text-xs text-indigo-600 dark:text-indigo-400 uppercase font-semibold tracking-wide mb-1">
                                {{ $product->category->name }}
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                <a href="{{ route('shop.show', $product) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            <div class="mb-2">
                                @php
                                    $today = \Carbon\Carbon::now()->toDateString();
                                    $activeDiscount = $product->discounts()
                                        ->where('start_date', '<=', $today)
                                        ->where('end_date', '>=', $today)
                                        ->first();
                                @endphp

                                @if($activeDiscount)
                                    <div class="flex items-center">
                                        <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format(($product->price * (100 - $activeDiscount->percentage)) / 100, 0, ',', '.') }}
                                        </span>
                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400 line-through">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                        <span class="ml-2 text-xs font-semibold text-white bg-red-500 px-2 py-1 rounded">
                                            -{{ $activeDiscount->percentage }}%
                                        </span>
                                    </div>
                                @else
                                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Stok: {{ $product->stock }}
                            </div>

                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 {{ $product->stock < 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ $product->stock < 1 ? 'disabled' : '' }}>
                                    {{ $product->stock < 1 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">
                            Tidak ada produk ditemukan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Coba ubah filter pencarian Anda.
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-shop-layout>
