@extends('layouts.app')

<head>
    <!-- Meta tags dan resource lainnya -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Orders</title>
</head>

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Pesanan Saya</h1>

    @if ($orders->isEmpty())
        <div class="text-center py-16">
            <p class="text-gray-500 text-lg">Tidak ada pesanan yang ditemukan.</p>
            <a href="{{ route('menus.index') }}" 
               class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                Mulai Pesan Sekarang
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 bg-white rounded-lg shadow-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-300 text-left text-sm uppercase text-gray-700">
                        <th class="px-6 py-3 border-b">ID</th>
                        <th class="px-6 py-3 border-b">Booth</th>
                        <th class="px-6 py-3 border-b">Total Harga</th>
                        <th class="px-6 py-3 border-b">Status</th>
                        <th class="px-6 py-3 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 border-b text-gray-800">{{ $order->id }}</td>
                            <td class="px-6 py-4 border-b text-gray-800">{{ $order->booth->name }}</td>
                            <td class="px-6 py-4 border-b text-gray-800">Rp {{ number_format($order->orderItems->sum(fn($item) => $item->menu->price * $item->quantity), 0, ',', '.') }}</td>
                            <td class="px-6 py-4 border-b">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                              {{ $order->status === 'completed' ? 'bg-green-100 text-green-600' : ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 border-b">
                                <a href="{{ route('orders.status', $order->id) }}" 
                                   class="text-blue-500 hover:text-blue-600 hover:underline">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
