<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pengiriman') }}
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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Daftar Pengiriman</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-700 border dark:border-gray-600">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">ID Pesanan</th>
                                    <th class="py-3 px-6 text-left">Pelanggan</th>
                                    <th class="py-3 px-6 text-left">Tanggal Pesanan</th>
                                    <th class="py-3 px-6 text-left">Kurir</th>
                                    <th class="py-3 px-6 text-left">No. Resi</th>
                                    <th class="py-3 px-6 text-left">Status Pengiriman</th>
                                    <th class="py-3 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 dark:text-gray-200 text-sm">
                                @forelse ($deliveries as $delivery)
                                    <tr class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="py-3 px-6">{{ $delivery->order->id ?? '-' }}</td>
                                        <td class="py-3 px-6">{{ $delivery->order->customer->name ?? '-' }}</td>
                                        <td class="py-3 px-6">{{ $delivery->order->order_date->format('d M Y H:i') ?? '-' }}</td>
                                        <td class="py-3 px-6">{{ $delivery->courier_name }}</td>
                                        <td class="py-3 px-6">{{ $delivery->tracking_number }}</td>
                                        <td class="py-3 px-6">
                                            <span class="px-2 py-1 rounded text-xs
                                                @if($delivery->status === 'delivered') bg-green-200 text-green-800
                                                @elseif($delivery->status === 'in_transit') bg-blue-200 text-blue-800
                                                @else bg-yellow-200 text-yellow-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <a href="{{ route('admin.deliveries.edit', $delivery->order) }}"
                                               class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200 mx-1">
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                    Update
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-3 px-6 text-center">Tidak ada data pengiriman</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $deliveries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
