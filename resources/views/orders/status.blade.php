@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-gray-50 border border-gray-200 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-4">Struk Pesanan</h1>
        
        <div class="border-b border-gray-300 pb-4 mb-4">
            <p class="text-sm text-gray-500">ID Pesanan: <span class="font-mono text-gray-800">{{ $order->id }}</span></p>
            <p class="text-sm text-gray-500">Tanggal: <span class="font-mono text-gray-800">{{ $order->created_at->format('d/m/Y H:i') }}</span></p>
        </div>

        {{-- Informasi Booth --}}
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Booth</h3>
            <p class="text-gray-800 font-mono">{{ $order->booth->name ?? 'Booth Tidak Ditemukan' }}</p>
        </div>

        {{-- Detail Pesanan --}}
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Detail Pesanan</h3>
            <ul class="text-sm text-gray-800 font-mono">
                @foreach ($order->orderItems as $item)
                    <li>{{ $item->menu->name }} x{{ $item->quantity }} - Rp {{ number_format($item->menu->price * $item->quantity, 0, ',', '.') }}</li>
                @endforeach
            </ul>
        </div>

        {{-- Total Harga --}}
        <div class="border-t border-gray-300 pt-4 mb-4">
            <p class="text-sm font-bold text-gray-800">Total Harga:</p>
            <p class="text-lg font-semibold text-gray-800 font-mono">Rp {{ number_format($order->orderItems->sum(fn($item) => $item->menu->price * $item->quantity), 0, ',', '.') }}</p>
        </div>

        {{-- Metode Pembayaran --}}
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Metode Pembayaran</h3>
            <p class="font-mono text-gray-800">{{ ucfirst($order->payment_method ?? 'Tidak Diketahui') }}</p>
        </div>

        {{-- Status Pesanan --}}
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Status Pesanan</h3>
            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $order->status === 'completed' ? 'bg-green-100 text-green-600' : ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        {{-- Bukti Pembayaran --}}
        @if ($order->payment && $order->payment->proof_image)
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Bukti Pembayaran</h3>
                <img src="{{ asset('storage/' . $order->payment->proof_image) }}" alt="Bukti Pembayaran" class="mt-2 w-full max-w-md mx-auto rounded-md shadow-md">
            </div>
        @endif

        {{-- Pesan Selesai --}}
        @if ($order->status == 'completed')
            <div class="mt-4">
                <p class="text-green-500 font-bold text-center">Pesanan selesai! Silakan ambil di booth.</p>
            </div>
        @endif
    </div>
</div>
@endsection
