<x-shop-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $product->name }}
            </h2>
            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Product Image -->
                        <div class="w-full h-80 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>

                        <!-- Product Details -->
                        <div>
                            <div class="text-sm text-indigo-600 dark:text-indigo-400 uppercase font-semibold tracking-wide mb-2">
                                {{ $product->category->name }}
                            </div>

                            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $product->name }}</h1>

                            <!-- Price & Discount -->
                            <div class="mb-6">
                                @if($activeDiscount)
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($product->price * (100 - $activeDiscount->percentage) / 100, 0, ',', '.') }}
                                        </span>
                                        <span class="ml-3 text-lg text-gray-500 dark:text-gray-400 line-through">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                        <span class="ml-3 text-sm font-semibold text-white bg-red-500 px-2 py-1 rounded">
                                            -{{ $activeDiscount->percentage }}%
                                        </span>
                                    </div>
                                    <div class="mt-2 text-sm text-red-600 dark:text-red-400">
                                        Diskon berlaku hingga {{ \Carbon\Carbon::parse($activeDiscount->end_date)->format('d M Y') }}
                                    </div>
                                @else
                                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Stock -->
                            <div class="mb-6">
                                <span class="text-gray-700 dark:text-gray-300">Stok:</span>
                                <span class="ml-2 font-medium {{ $product->stock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $product->stock > 0 ? $product->stock : 'Habis' }}
                                </span>
                            </div>

                            <!-- Description -->
                            <div class="mb-8">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Deskripsi</h3>
                                <div class="text-gray-700 dark:text-gray-300 prose dark:prose-invert">
                                    {{ $product->description ?? 'Tidak ada deskripsi tersedia untuk produk ini.' }}
                                </div>
                            </div>

                            <!-- Add to Cart -->
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <label for="quantity" class="sr-only">Jumlah</label>
                                    <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-md">
                                        <button type="button" class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <input type="number" id="quantity" name="quantity" min="1" value="1" class="w-12 text-center border-0 focus:ring-0 dark:bg-gray-800 dark:text-white">
                                        <button type="button" class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <button class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-md transition duration-300">
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Produk Terkait</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                            <div class="p-4">
                                <!-- Product Image (placeholder) -->
                                <div class="w-full h-40 bg-gray-200 dark:bg-gray-700 rounded-md mb-4 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>

                                <!-- Product Name -->
                                <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    <a href="{{ route('shop.show', $relatedProduct) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $relatedProduct->name }}
                                    </a>
                                </h3>

                                <!-- Product Price -->
                                <div class="mb-2">
                                    @php
                                        $today = \Carbon\Carbon::now()->toDateString();
                                        $relatedDiscount = $relatedProduct->discounts()
                                            ->where('start_date', '<=', $today)
                                            ->where('end_date', '>=', $today)
                                            ->first();
                                    @endphp

                                    @if($relatedDiscount)
                                        <div class="flex items-center">
                                            <span class="text-md font-bold text-gray-900 dark:text-gray-100">
                                                Rp {{ number_format($relatedProduct->price * (100 - $relatedDiscount->percentage) / 100, 0, ',', '.') }}
                                            </span>
                                            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400 line-through">
                                                Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-md font-bold text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-shop-layout>
