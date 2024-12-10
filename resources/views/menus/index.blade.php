@extends('layouts.app')

<head>
    <!-- Meta tags dan resource lainnya -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Daftar Menu</title>
</head>

@section('content')
<div class="container mx-auto py-8" style="padding-left: 10rem; padding-right: 10rem;">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-left">Menu di Booth: {{ $booth->name }}</h1>

    {{-- Deskripsi Booth --}}
    <p class="text-gray-600 mb-8 text-left">{{ $booth->description }}</p>

    <form action="{{ route('orders.bulkCreate', $booth->id) }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse ($menus as $menu)
                <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col items-center">
                    <img src="{{ file_exists(public_path('storage/' . $menu->name . '.jpg')) ? asset('storage/' . $menu->name . '.jpg') : asset('images/default-menu.jpg') }}" 
                         alt="{{ $menu->name }}" 
                         class="w-32 h-32 object-cover rounded-md mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ $menu->name }}</h2>
                    <p class="text-gray-600 mb-2">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>

                    {{-- Tombol Tambah dan Kurang --}}
                    <div class="flex items-center gap-2">
                        <button type="button" 
                            class="text-blue-500 border border-blue-500 px-2 py-1 rounded-md hover:bg-blue-500 hover:text-white"
                            onclick="changeQuantity('qty_{{ $menu->id }}', -1)">
                            &minus;
                        </button>
                        <input type="number" name="quantities[{{ $menu->id }}]" id="qty_{{ $menu->id }}" 
                               class="w-10 p-2 border border-white rounded-md text-center no-spinner" 
                               value="0" min="0">
                        <button type="button" 
                        class="text-blue-500 border border-blue-500 px-2 py-1 rounded-md hover:bg-blue-500 hover:text-white"
                        onclick="changeQuantity('qty_{{ $menu->id }}', 1)">
                            &plus;
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center">
                    <p class="text-gray-600">Belum ada menu tersedia di booth ini.</p>
                </div>
            @endforelse
        </div>

        @if ($menus->isNotEmpty())
            <div class="mt-6 bg-white shadow-lg rounded-lg p-6">
                <div class="mb-4">
                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran:</label>
                    <select name="payment_method" id="payment_method" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                        <option value="cash">Tunai</option>
                        @if ($booth->name !== 'Pakde') {{-- QRIS hanya aktif jika booth bukan Pakde --}}
                            <option value="qris">QRIS</option>
                        @else
                            <option value="qris" disabled>QRIS (Tidak Tersedia)</option>
                        @endif
                    </select>
                </div>

                {{-- Gambar QRIS --}}
                <div id="qris_image" class="mt-4 hidden">
                    <label for="qris_code" class="block font-medium text-sm text-gray-700">Scan QRIS:</label>
                    <img src="{{ asset('storage/' . $booth->name . '.jpg') }}" alt="QRIS Penjual" class="mt-2 w-60 mx-auto">
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 w-full">
                    Pesan Sekarang
                </button>
            </div>
        @endif
    </form>
</div>

{{-- Tambahkan CSS untuk menghilangkan spinner pada input number --}}
<style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield; /* Firefox */
    }
</style>

{{-- Tambahkan JavaScript untuk tombol tambah/kurang --}}
<script>
    function changeQuantity(inputId, delta) {
        const input = document.getElementById(inputId);
        const currentValue = parseInt(input.value, 10) || 0;
        const newValue = Math.max(0, currentValue + delta); // Pastikan tidak kurang dari 0
        input.value = newValue;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const paymentMethod = document.getElementById('payment_method');
        const qrisImage = document.getElementById('qris_image');

        paymentMethod.addEventListener('change', function () {
            if (paymentMethod.value === 'qris') {
                qrisImage.classList.remove('hidden');
            } else {
                qrisImage.classList.add('hidden');
            }
        });
    });
</script>
@endsection
