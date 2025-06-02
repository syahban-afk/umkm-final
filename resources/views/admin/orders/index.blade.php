<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
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
                    <h3 class="text-lg font-semibold mb-4">Daftar Pesanan</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-700 border dark:border-gray-600">
                            <thead>
                                <tr
                                    class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">ID</th>
                                    <th class="py-3 px-6 text-left">Pelanggan</th>
                                    <th class="py-3 px-6 text-left">Tanggal</th>
                                    <th class="py-3 px-6 text-left">Total</th>
                                    <th class="py-3 px-6 text-left">Status Pesanan</th>
                                    <th class="py-3 px-6 text-left">Status Pembayaran</th>
                                    <th class="py-3 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 dark:text-gray-200 text-sm">
                                @forelse ($orders as $order)
                                    <tr
                                        class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="py-3 px-6">{{ $loop->iteration }}</td>
                                        <td class="py-3 px-6">{{ $order->customer->name ?? '-' }}</td>
                                        <td class="py-3 px-6">{{ $order->order_date->format('d M Y H:i') }}</td>
                                        <td class="py-3 px-6">Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-6">
                                            <span
                                                class="px-2 py-1 rounded text-xs
                                                @if ($order->status === 'completed') bg-green-200 text-green-800
                                                @elseif($order->status === 'processing') bg-blue-200 text-blue-800
                                                @elseif($order->status === 'cancelled') bg-red-200 text-red-800
                                                @else bg-yellow-200 text-yellow-800 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">
                                            @if ($order->payment)
                                                <span
                                                    class="px-2 py-1 rounded text-xs
                                                    @if ($order->payment->status === 'paid') bg-green-200 text-green-800
                                                    @elseif($order->payment->status === 'cancelled') bg-red-200 text-red-800
                                                    @else bg-yellow-200 text-yellow-800 @endif">
                                                    {{ ucfirst($order->payment->status) }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 rounded text-xs bg-gray-200 text-gray-800">Tidak
                                                    Ada</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200">
                                                    <span
                                                        class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                        Detail
                                                    </span>
                                                </a>

                                                <form action="{{ route('admin.orders.update-status', $order) }}"
                                                    method="POST" class="inline-flex items-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status"
                                                        class="text-xs rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:ring focus:ring-indigo-500 mr-1">
                                                        <option value="pending"
                                                            {{ $order->status === 'pending' ? 'selected' : '' }}>
                                                            Pending</option>
                                                        <option value="processing"
                                                            {{ $order->status === 'processing' ? 'selected' : '' }}>
                                                            Processing</option>
                                                        <option value="completed"
                                                            {{ $order->status === 'completed' ? 'selected' : '' }}>
                                                            Completed</option>
                                                    </select>
                                                    @if ($order->status === 'completed')
                                                        <button type="button"
                                                            class="bg-gray-100 text-gray-400 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed"
                                                            disabled>
                                                            Done
                                                        </button>
                                                    @else
                                                        <button type="submit"
                                                            class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-800">
                                                            Update
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-3 px-6 text-center">Tidak ada data pesanan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
