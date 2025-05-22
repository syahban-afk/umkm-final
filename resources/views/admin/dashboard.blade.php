<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">Navigasi Admin</h3>

                <ul class="list-disc pl-6 space-y-2 text-blue-600 dark:text-white">
                    <li><a href="{{ route('admin.categories.index') }}">Kategori</a></li>
                    <li><a href="{{ route('admin.deliveries.index') }}">Pengiriman</a></li>
                    <li><a href="{{ route('admin.discount-categories.index') }}">Kategori Diskon</a></li>
                    <li><a href="{{ route('admin.discounts.index') }}">Diskon</a></li>
                    <li><a href="{{ route('admin.orders.index') }}">Pesanan</a></li>
                    <li><a href="{{ route('admin.products.index') }}">Produk</a></li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
