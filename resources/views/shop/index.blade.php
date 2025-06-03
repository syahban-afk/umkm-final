<x-shop-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Carausel Section -->
            <div id="animation-carousel" class="relative w-full py-5" data-carousel="slide" data-carousel-interval="5000">
                <!-- Carousel wrapper -->
                <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
                    <!-- Item 1 -->
                    <div class="hidden duration-200 ease-linear" data-carousel-item>
                        <img src={{asset("/corousel/bag.jpeg")}}
                            class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                            alt="Iklan guys">
                    </div>
                    <!-- Item 2 -->
                    <div class="hidden duration-200 ease-linear" data-carousel-item>
                        <img src={{asset("/corousel/handphone.png")}}
                            class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                            alt="Iklan guys">
                    </div>
                    <!-- Item 3 -->
                    <div class="hidden duration-200 ease-linear" data-carousel-item="active">
                        <img src={{asset("/corousel/headphone.png")}}
                            class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                            alt="Iklan guys">
                    </div>
                    <!-- Item 4 -->
                    <div class="hidden duration-200 ease-linear" data-carousel-item>
                        <img src={{asset("/corousel/laptop.png")}}
                            class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                            alt="Iklan guys">
                    </div>
                    <!-- Item 5 -->
                    <div class="hidden duration-200 ease-linear" data-carousel-item>
                        <img src={{asset("/corousel/shoes.jpeg")}}
                            class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                            alt="Iklan guys">
                    </div>
                </div>
                <!-- Slider controls -->
                <button type="button"
                    class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                    data-carousel-prev>
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 1 1 5l4 4" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button"
                    class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                    data-carousel-next>
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </span>
                </button>
            </div>

            <!-- Tombol Toggle (Mobile Only) -->
            <div class="md:hidden flex justify-end mb-4">
                <button id="toggleFilterBtn"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Tampilkan Filter
                </button>
            </div>

            <!-- Wrapper: Sidebar + Main Content -->
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Sidebar Filter -->
                <aside id="filterSidebar"
                    class="w-full md:w-1/4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mb-6 md:mb-0 hidden md:block">
                    <form action="{{ route('shop.index') }}" method="GET" class="space-y-4 sticky top-[150px]">
                        <!-- Pencarian -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Cari Produk
                            </label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Kategori
                            </label>
                            <select name="category" id="category"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Diskon -->
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

                        <!-- Tombol Filter -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                                Filter
                            </button>
                        </div>
                    </form>
                </aside>

                <!-- Konten Produk -->
                <main class="w-full md:w-3/4 pl-0 md:pl-4">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <div class="text-center text-lg md:text-xl font-semibold text-black dark:text-white py-5">
                            <p>Featured Products</p>
                        </div>

                        @if (session('success'))
                            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                                role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                                role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        <!-- Products Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 py-5">
                            @forelse($products as $product)
                                <div
                                    class="
                                    bg-white dark:bg-gray-900 overflow-hidden sm:rounded-lg h-full flex flex-col
transition-transform transition-shadow duration-300 transform
shadow-md hover:shadow-xl hover:scale-105
shadow-[0_0_10px_rgba(99,102,241,0.3)] dark:shadow-[0_0_15px_rgba(99,102,241,0.5)]
dark:hover:shadow-[0_0_20px_rgba(99,102,241,0.6)]
">
                                    <div class="p-4 flex flex-col h-full">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-48 object-cover rounded-md mb-4">
                                        @else
                                            <div
                                                class="w-full h-48 bg-gray-200 dark:bg-gray-700 rounded-md mb-4 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif

                                        <div
                                            class="text-xs text-indigo-600 dark:text-indigo-400 uppercase font-semibold tracking-wide mb-1">
                                            {{ $product->category->name }}
                                        </div>

                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                <a href="{{ route('shop.show', $product) }}"
                                                    class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                    {{ $product->name }}
                                                </a>
                                            </h3>
                                            @auth
                                                @php
                                                    $inWishlist = false;
                                                    $hasCustomer = Auth::user()->customer ? true : false;
                                                    if ($hasCustomer) {
                                                        $inWishlist = \App\Models\Wishlist::where(
                                                            'customer_id',
                                                            Auth::user()->customer->id,
                                                        )
                                                            ->where('product_id', $product->id)
                                                            ->exists();
                                                    }
                                                @endphp
                                                <form action="{{ route('wishlist.add', $product->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="ml-2 transition-colors duration-300 {{ !$hasCustomer ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                        {{ !$hasCustomer ? 'disabled' : '' }}
                                                        title="{{ $inWishlist ? 'Hapus dari wishlist' : 'Tambah ke wishlist' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-6 w-6 {{ $inWishlist ? 'text-red-500 fill-current' : 'text-gray-400' }}"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endauth
                                        </div>

                                        <div class="mb-2">
                                            @php
                                                $today = \Carbon\Carbon::now()->toDateString();
                                                $activeDiscount = $product
                                                    ->discounts()
                                                    ->where('start_date', '<=', $today)
                                                    ->where('end_date', '>=', $today)
                                                    ->first();
                                            @endphp

                                            @if ($activeDiscount)
                                                <div class="flex items-center">
                                                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                                        Rp
                                                        {{ number_format(($product->price * (100 - $activeDiscount->percentage)) / 100, 0, ',', '.') }}
                                                    </span>
                                                    <span
                                                        class="ml-2 text-sm text-gray-500 dark:text-gray-400 line-through">
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    </span>
                                                    <span
                                                        class="ml-2 text-xs font-semibold text-white bg-green-500 px-2 py-1 rounded">
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
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
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
                </main>
            </div>



        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('toggleFilterBtn').addEventListener('click', () => {
                const sidebar = document.getElementById('filterSidebar');
                sidebar.classList.toggle('hidden');
                const btn = document.getElementById('toggleFilterBtn');
                if (sidebar.classList.contains('hidden')) {
                    btn.textContent = 'Tampilkan Filter';
                } else {
                    btn.textContent = 'Sembunyikan Filter';
                }
            });
        </script>
    @endpush
</x-shop-layout>
