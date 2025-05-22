<x-shop-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Upload Bukti Pembayaran') }}
            </h2>
            <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Detail Pesanan #{{ $order->id }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Tanggal Pesanan: <span class="font-medium text-gray-900 dark:text-white">{{ $order->order_date->format('d M Y') }}</span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total: <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Status:
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                        @if($order->status == 'processing') bg-blue-100 text-blue-800 @endif
                                        @if($order->status == 'completed') bg-green-100 text-green-800 @endif
                                        @if($order->status == 'cancelled') bg-red-100 text-red-800 @endif
                                    ">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Metode Pembayaran: <span class="font-medium text-gray-900 dark:text-white">{{ $order->payment ? ucfirst($order->payment->payment_method) : 'Belum ditentukan' }}</span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Status Pembayaran:
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if(!$order->payment || $order->payment->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                        @if($order->payment && $order->payment->status == 'paid') bg-green-100 text-green-800 @endif
                                        @if($order->payment && $order->payment->status == 'failed') bg-red-100 text-red-800 @endif
                                    ">
                                        {{ $order->payment ? ucfirst($order->payment->status) : 'Menunggu Pembayaran' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Rekening</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Silakan transfer ke salah satu rekening berikut:</p>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Bank BCA</span>
                                    <span class="text-sm text-gray-900 dark:text-white">1234567890 (a.n. UMKM Marketplace)</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Bank Mandiri</span>
                                    <span class="text-sm text-gray-900 dark:text-white">0987654321 (a.n. UMKM Marketplace)</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Bank BNI</span>
                                    <span class="text-sm text-gray-900 dark:text-white">1122334455 (a.n. UMKM Marketplace)</span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Jumlah yang harus ditransfer: <span class="font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Upload Bukti Pembayaran</h3>
                        <form action="{{ route('orders.payment.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <x-input-label for="payment_method" :value="__('Metode Pembayaran')" />
                                <select id="payment_method" name="payment_method" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Pilih Metode Pembayaran</option>
                                    <option value="bca">Bank BCA</option>
                                    <option value="mandiri">Bank Mandiri</option>
                                    <option value="bni">Bank BNI</option>
                                </select>
                                <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="payment_date" :value="__('Tanggal Pembayaran')" />
                                <x-text-input id="payment_date" class="block mt-1 w-full" type="date" name="payment_date" :value="old('payment_date', date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="payment_proof" :value="__('Bukti Pembayaran')" />
                                <input id="payment_proof" name="payment_proof" type="file" accept="image/*" class="block mt-1 w-full text-sm text-gray-900 dark:text-white border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 dark:border-gray-600 focus:outline-none" required>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload bukti transfer dalam format JPG, PNG, atau PDF (max 2MB)</p>
                                <x-input-error :messages="$errors->get('payment_proof')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="notes" :value="__('Catatan (Opsional)')" />
                                <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <x-primary-button>
                                    {{ __('Upload Bukti Pembayaran') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shop-layout>
