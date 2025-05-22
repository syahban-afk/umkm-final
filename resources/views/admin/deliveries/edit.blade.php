<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update Pengiriman') }} - Order #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('admin.deliveries.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Kurir -->
                            <div>
                                <label for="courier_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kurir</label>
                                <input type="text" name="courier_name" id="courier_name" value="{{ old('courier_name', $delivery->courier_name) }}"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:ring focus:ring-indigo-500" required>
                                @error('courier_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tracking Number -->
                            <div>
                                <label for="tracking_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Resi</label>
                                <input type="text" name="tracking_number" id="tracking_number" value="{{ old('tracking_number', $delivery->tracking_number) }}"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:ring focus:ring-indigo-500" required>
                                @error('tracking_number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status Pengiriman -->
                            <div class="md:col-span-2">
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Pengiriman</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:ring focus:ring-indigo-500" required>
                                    <option value="pending" {{ old('status', $delivery->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_transit" {{ old('status', $delivery->status) === 'in_transit' ? 'selected' : '' }}>Dalam Pengiriman</option>
                                    <option value="delivered" {{ old('status', $delivery->status) === 'delivered' ? 'selected' : '' }}>Terkirim</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

                            <a href="{{ route('admin.deliveries.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 active:bg-gray-500 dark:active:bg-gray-600 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
