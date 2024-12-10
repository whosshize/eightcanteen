@extends('layouts.app')

<head>
    <!-- Meta tags dan resource lainnya -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Eighteen</title>
</head>

@section('content')
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Hero Section -->
                <div class="p-8 text-center bg-blue-500 rounded-t-lg">
                    <h1 class="text-4xl font-bold text-white mb-2">Selamat Datang di Eighteen</h1>
                    <p class="text-lg text-white mb-4">Platform pemesanan kantin online yang memudahkan aktivitas sekolah Anda.</p>
                    <div>
                        <a href="{{ route('booths.index') }}" 
                           class="inline-block px-6 py-3 bg-white text-blue-500 font-semibold rounded-lg shadow-md hover:bg-gray-100 transition duration-300">
                            Pesan sekarang!
                        </a>
                    </div>
                </div>

                <!-- About Section -->
                <div class="p-6 bg-gray-50">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-3">Tentang Eighteen</h2>
                    <p class="text-gray-600 leading-6">
                        Eighteen adalah platform pemesanan cantina online yang dirancang khusus untuk membantu siswa dan guru
                        memesan makanan tanpa perlu mengantri. Dengan berbagai booth yang tersedia, pembayaran yang fleksibel,
                        dan pengalaman pemesanan yang cepat, kami hadir untuk mendukung kenyamanan Anda setiap hari di sekolah.
                    </p>
                </div>

                <!-- Features Section -->
                <div class="p-6 bg-white">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Fitur Kami</h3>
                    <ul class="list-disc pl-5 text-gray-600 leading-6">
                        <li>Memesan makanan tanpa harus mengantri panjang.</li>
                        <li>Pilihan booth dan menu yang lengkap.</li>
                        <li>Pembayaran fleksibel dengan tunai atau QRIS.</li>
                        <li>Tampilan antarmuka yang intuitif dan mudah digunakan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection