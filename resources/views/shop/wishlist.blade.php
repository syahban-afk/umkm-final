<x-shop-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Wishlist & Review') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tab Navigation -->
            <div class="mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button onclick="showTab('wishlist')" id="wishlist-tab"
                            class="tab-button border-indigo-500 text-indigo-600 dark:text-indigo-400 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Wishlist
                        </button>
                        <button onclick="showTab('reviews')" id="reviews-tab"
                            class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Review Produk
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Wishlist Tab -->
            <div id="wishlist-content" class="tab-content">
                @if (isset($wishlists) && count($wishlists) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($wishlists as $item)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                                <div class="h-48">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="bg-gray-200 dark:bg-gray-700 h-full flex items-center justify-center">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white truncate">
                                        {{ $item->product->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->product->category->name }}</p>
                                    <div class="mt-2 flex items-center justify-between">
                                        @php
                                            $today = \Carbon\Carbon::now()->toDateString();
                                            $activeDiscount = $item->product->discounts()
                                                ->where('start_date', '<=', $today)
                                                ->where('end_date', '>=', $today)
                                                ->first();
                                        @endphp

                                        @if($activeDiscount)
                                            <div class="flex items-center">
                                                <span class="text-gray-900 dark:text-white font-bold">
                                                    Rp {{ number_format(($item->product->price * (100 - $activeDiscount->percentage)) / 100, 0, ',', '.') }}
                                                </span>
                                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400 line-through">
                                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                </span>
                                                <span class="ml-2 text-xs font-semibold text-white bg-green-500 px-2 py-1 rounded">
                                                    -{{ $activeDiscount->percentage }}%
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-gray-900 dark:text-white font-bold">
                                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-4 flex space-x-2">
                                        <a href="{{ route('shop.show', $item->product->id) }}"
                                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                            Lihat
                                        </a>
                                        <form action="{{ route('wishlist.remove', $item->id) }}" method="POST"
                                            class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini dari wishlist?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Wishlist Kosong</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum memiliki produk dalam
                                wishlist.</p>
                            <div class="mt-6">
                                <a href="{{ route('shop.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Jelajahi Produk
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Reviews Tab -->
            <div id="reviews-content" class="tab-content hidden">
                @if (isset($purchasedProducts) && count($purchasedProducts) > 0)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Produk yang Dapat Direview</h3>
                            <div class="space-y-6">
                                @foreach ($purchasedProducts as $product)
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-b-0 last:pb-0">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 h-12 w-12 overflow-hidden rounded-md">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div class="bg-gray-200 dark:bg-gray-700 h-full w-full flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <div class="flex items-center justify-between">
                                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                                        {{ $product->name }}</h4>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Dibeli pada:
                                                        {{ $product->pivot->created_at->format('d M Y') }}</span>
                                                </div>
                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $product->category->name }}</p>

                                                @if ($product->userReview)
                                                    <div class="mt-4 bg-gray-50 dark:bg-gray-700 rounded-md p-4">
                                                        <div class="flex items-center mb-2">
                                                            <span
                                                                class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Rating
                                                                Anda:</span>
                                                            <div class="flex items-center">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-5 w-5 {{ $i <= $product->userReview->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}"
                                                                        viewBox="0 0 20 20" fill="currentColor">
                                                                        <path
                                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                    </svg>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                                            {{ $product->userReview->comment }}</p>
                                                        <div class="mt-2 flex justify-end">
                                                            <form
                                                                action="{{ route('reviews.delete', $product->userReview->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-sm text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                                    Hapus Review
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @else
                                                    <form action="{{ route('reviews.store') }}" method="POST"
                                                        class="mt-4">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">

                                                        <div class="mb-4">
                                                            <label for="rating-{{ $product->id }}"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                                                            <div class="flex items-center">
                                                                <div class="flex items-center"
                                                                    id="rating-stars-{{ $product->id }}">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <button type="button"
                                                                            onclick="setRating('{{ $product->id }}', {{ $i }})"
                                                                            class="rating-star text-gray-300 dark:text-gray-600 focus:outline-none">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                class="h-6 w-6" viewBox="0 0 20 20"
                                                                                fill="currentColor">
                                                                                <path
                                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                            </svg>
                                                                        </button>
                                                                    @endfor
                                                                </div>
                                                                <input type="hidden" name="rating"
                                                                    id="rating-{{ $product->id }}" value="0"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        <div class="mb-4">
                                                            <label for="comment-{{ $product->id }}"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Komentar</label>
                                                            <textarea id="comment-{{ $product->id }}" name="comment" rows="3"
                                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                required></textarea>
                                                        </div>

                                                        <div class="flex justify-end">
                                                            <button type="submit"
                                                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                                Kirim Review
                                                            </button>
                                                        </div>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum Ada Produk</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum memiliki produk yang
                                dapat direview.</p>
                            <div class="mt-6">
                                <a href="{{ route('shop.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Belanja Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
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
                    button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700',
                        'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                });

                document.getElementById(tabName + '-tab').classList.remove('border-transparent', 'text-gray-500',
                    'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                document.getElementById(tabName + '-tab').classList.add('border-indigo-500', 'text-indigo-600',
                    'dark:text-indigo-400');
            }

            function setRating(productId, rating) {
                // Update hidden input value
                document.getElementById('rating-' + productId).value = rating;

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
    @endpush
</x-shop-layout>
