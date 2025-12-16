@extends('layouts.app')

@section('content')
@php
$merchHighlightHtml = setting('merch_highlight_html', null);



use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

/**
 * Safe getter for settings:
 * - tries cache key setting_{key}
 * - falls back to Setting::where('key', $key)->value('value')
 * - caches the retrieved value for 1 hour
 */
$getSetting = function(string $key, $default = null) {
    $cacheKey = 'setting_'.$key;
    $v = Cache::get($cacheKey);
    if ($v === null) {
        $v = Setting::where('key', $key)->value('value');
        if ($v !== null) {
            Cache::put($cacheKey, $v, 3600);
        }
    }
    return $v ?? $default;
};

@endphp


<!-- Hero Section (enhanced) -->
<section class="pt-40 md:pt-52 pb-12 bg-gradient-to-b from-yellow-50 via-white to-white">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Top Title -->
        <div class="text-center mb-10 md:mb-14">
            <h1 class="text-7xl md:text-8xl font-extrabold text-yellow-500 leading-tight">Merchandise Pandawa</h1>
            <!-- decorative strip removed -->
            <p class="mt-6 text-lg md:text-xl text-gray-600 max-w-4xl mx-auto">Koleksi eksklusif dengan desain premium yang mencerminkan identitas dan semangat Pandawa. Tunjukkan kebanggaanmu menjadi bagian dari komunitas kami!</p>
        </div>

        <!-- CTAs -->
        <div class="flex flex-col md:flex-row items-center justify-center gap-4 mb-10">
            <a href="#products" class="inline-block px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg shadow hover:shadow-md transition">Lihat Koleksi</a>
            <a href="#how-to-order" class="inline-block px-6 py-3 border border-yellow-500 text-yellow-600 font-semibold rounded-lg hover:bg-yellow-50 transition">Cara Memesan</a>
            <a href="#contact" class="inline-block px-4 py-2 text-sm text-gray-600 underline">Hubungi Kami</a>
        </div>

        <!-- Highlight Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-xl shadow text-center">
                <div class="text-4xl font-bold text-gradient mb-2">100%</div>
                <p class="text-gray-600">Desain Original Pandawa</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow text-center">
                <div class="text-4xl font-bold text-gradient mb-2">Premium</div>
                <p class="text-gray-600">Kualitas Bahan Terbaik</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow text-center">
                <div class="text-4xl font-bold text-gradient mb-2">Fast</div>
                <p class="text-gray-600">Pengiriman Cepat & Aman</p>
            </div>
        </div>

        <!-- Featured Product Highlight -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-10">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Koleksi Terbaru</h3>
            <p class="text-gray-600 text-center max-w-3xl mx-auto">Dapatkan merchandise eksklusif Pandawa dengan desain terbaru yang terinspirasi dari karya seni komunitas. Setiap produk dirancang dengan detail sempurna untuk menunjukkan identitas dan kebanggaan Pandawa!</p>
        </div>
    </div>
</section>


@if($merchHighlightHtml)
<div class="bg-white p-6 rounded-2xl shadow mb-8">{!! $merchHighlightHtml !!}</div>
@endif

{{-- stats, featured, products (use same code as previous) --}}
</div>
</section>

<section id="products" class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Produk Kami</h2>
            <div class="w-16 h-1 gradient-gold mx-auto"></div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach ($items as $item)
            {{-- same card markup as original but keep it here to be complete --}}
            <div class="merch-card bg-white rounded-2xl overflow-hidden shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col">
                <div class="h-80 w-full overflow-hidden flex-shrink-0">
                    @php
                    $thumb = $item->gambar_utama ?: ($item->images->count() ? $item->images[0]->image : null);
                    @endphp
                    @if ($thumb)
                    <img src="{{ asset('uploads/merch/'.$thumb) }}" alt="{{ $item->nama_barang }}" class="w-full h-full object-cover">
                    @else
                    <img src="{{ asset('noimage.png') }}" class="w-full h-full object-cover">
                    @endif
                </div>

                <div class="p-6 flex flex-col flex-grow">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $item->nama_barang }}</h3>
                    <p class="text-gray-600 mb-6 flex-grow">{{ $item->deskripsi }}</p>
                    <div class="flex justify-between items-center mt-auto pt-4 border-t border-gray-200">
                        <span class="text-2xl font-bold text-gradient">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                        <a href="{{ route('merchdetail', $item->id) }}" class="bg-yellow-500 text-black font-bold px-6 py-2 rounded-full hover:shadow-lg transition-all">Pesan</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

<!-- How to Order Section -->
<section id="how-to-order" class="py-20 bg-gradient-to-b from-yellow-50 to-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-5xl font-bold text-gray-800 mb-4">Cara Memesan</h2>
            <div class="w-24 h-1 gradient-gold mx-auto mb-6"></div>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">Proses pemesanan mudah dan cepat. Ikuti langkah-langkah sederhana berikut untuk mendapatkan merchandise Pandawa favorit Anda!</p>
        </div>
        <!-- Steps Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Step 1 -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-500 text-white text-2xl font-bold mb-4">1</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Pilih Produk</h3>
                <p class="text-gray-600 leading-relaxed">Jelajahi koleksi merchandise kami dan pilih produk yang Anda inginkan. Lihat detail, harga, dan ketersediaan produk.</p>
            </div>
            <!-- Step 2 -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-500 text-white text-2xl font-bold mb-4">2</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Isi Form Pemesanan</h3>
                <p class="text-gray-600 leading-relaxed">Isi data pribadi Anda dengan lengkap, pilih ukuran/warna, dan masukkan jumlah produk yang ingin dipesan.</p>
            </div>
            <!-- Step 3 -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-500 text-white text-2xl font-bold mb-4">3</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Lakukan Pembayaran</h3>
                <p class="text-gray-600 leading-relaxed">Lakukan pembayaran sesuai metode yang tersedia. Setelah itu anda akan diarahkan ke nomor whatsaap.</p>
            </div>
            <!-- Step 4 -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-500 text-white text-2xl font-bold mb-4">4</div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Terima Pesanan</h3>
                <p class="text-gray-600 leading-relaxed">Barang akan dikemas dengan rapi dan dikirimkan ke alamat Anda. Tracking nomor diberikan untuk memantau pengiriman.</p>
            </div>
        </div>
        <!-- Additional Info -->
        <div class="bg-white rounded-2xl shadow-lg p-10">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Info Penting</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="border-l-4 border-yellow-500 pl-4">
                    <h4 class="text-lg font-bold text-gray-800 mb-2">⏱ Waktu Pemrosesan</h4>
                    <p class="text-gray-600">Pesanan Anda akan diproses dalam 1-2 hari kerja setelah pembayaran dikonfirmasi.</p>
                </div>
                <div class="border-l-4 border-yellow-500 pl-4">
                    <h4 class="text-lg font-bold text-gray-800 mb-2">🚚 Pengiriman</h4>
                    <p class="text-gray-600">Kami bekerja sama dengan kurir terpercaya.Pelanggan juga dapat memilih jika ingin melakukan pick - up atau pun diantar kurir.</p>
                </div>
                <div class="border-l-4 border-yellow-500 pl-4">
                    <h4 class="text-lg font-bold text-gray-800 mb-2">❓ Pertanyaan</h4>
                    <p class="text-gray-600">Ada pertanyaan? Hubungi kami melalui WhatsApp atau email untuk bantuan lebih lanjut.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-4">Siap Memesan?</h2>
        <p class="text-lg mb-8 max-w-3xl mx-auto">Jangan lewatkan koleksi eksklusif Pandawa! Dapatkan merchandise favorit Anda sekarang juga dan tunjukkan kebanggaanmu.</p>
        <a href="#products" class="inline-block bg-white text-yellow-600 font-bold px-8 py-3 rounded-full hover:shadow-lg transition">
            Mulai Berbelanja Sekarang
        </a>
    </div>
</section>



@endsection