@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-16">
    <div class="bg-white shadow-xl p-8 rounded-xl">
        <!-- Carousel -->
        <div class="relative w-full h-80 overflow-hidden rounded-lg mb-6">
            <div id="carousel" class="flex transition-all duration-500">
                @if($item->gambar_utama)
                    <img src="{{ asset('uploads/merch/'.$item->gambar_utama) }}" class="w-full h-80 object-contain flex-shrink-0">
                @endif

                @foreach ($item->images as $img)
                    <img src="{{ asset('uploads/merch/'.$img->image) }}" class="w-full h-80 object-contain flex-shrink-0">
                @endforeach

                @if(!$item->gambar_utama && $item->images->count() == 0)
                    <img src="{{ asset('noimage.png') }}" class="w-full h-80 object-contain flex-shrink-0">
                @endif
            </div>

            @php $totalSlides = ($item->gambar_utama ? 1 : 0) + $item->images->count(); @endphp

            @if($totalSlides > 1)
                <button onclick="prevSlide()" class="absolute top-1/2 left-3 bg-black bg-opacity-50 text-white px-3 py-2 rounded-full">‹</button>
                <button onclick="nextSlide()" class="absolute top-1/2 right-3 bg-black bg-opacity-50 text-white px-3 py-2 rounded-full">›</button>
            @endif
        </div>

        <script>
            let slide = 0;
            const total = @json($totalSlides);
            const track = document.getElementById('carousel');

            function updateSlide() {
                track.style.transform = `translateX(-${slide * 100}%)`;
            }
            function nextSlide() { slide = (slide + 1) % total; updateSlide(); }
            function prevSlide() { slide = (slide - 1 + total) % total; updateSlide(); }
        </script>

        <h2 class="text-4xl font-bold mb-2">{{ $item->nama_barang }}</h2>
        <p class="text-gray-600 mb-4">{{ $item->deskripsi }}</p>
        <p class="text-2xl font-bold text-gradient mb-8">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>

        <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="id_barang" value="{{ $item->id }}">
            <div>
                <label>Nama Pemesan</label>
                <input type="text" name="nama" class="w-full p-3 border rounded" required>
            </div>
            <div>
                <label>Nomor HP</label>
                <input type="text" name="phone" class="w-full p-3 border rounded" required>
            </div>
            <div id="order-form" data-price="{{ $item->harga }}">
                <label>Jumlah</label>
                <div class="flex gap-4">
                    <input type="number" id="jumlah" name="jumlah" class="w-full p-3 border rounded" min="1" value="1" required>
                    <input type="text" id="total_price" class="w-1/2 p-3 border rounded bg-gray-100" readonly value="Rp {{ number_format($item->harga, 0, ',', '.') }}">
                </div>
                <input type="hidden" name="total_harga" id="total_harga_input" value="{{ $item->harga }}">
            </div>
            <div>
                <label>Catatan</label>
                <textarea name="catatan" class="w-full p-3 border rounded"></textarea>
            </div>
            <div>
                @php
                    use App\Models\Setting;
                    $paymentHtml = Setting::where('key', 'order_payment_instructions')->value('value') ?? '';
                @endphp
                @if(!empty($paymentHtml))
                    {!! $paymentHtml !!}
                @else
                    <label>Bukti Transfer : Transfer ke norek berikut - 17759807 BNI</label>
                @endif
                <input type="file" name="bukti" class="w-full p-3 border rounded" required>
            </div>
            <button class="gradient-gold text-black font-bold w-full py-3 rounded-lg">Kirim Pesanan</button>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function(){
                const orderForm = document.getElementById('order-form');
                if(!orderForm) return;
                const unitPrice = Number(orderForm.dataset.price) || 0;
                const qtyInput = document.getElementById('jumlah');
                const totalInput = document.getElementById('total_price');
                const totalHidden = document.getElementById('total_harga_input');

                function formatRupiah(num){
                    return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }

                function updateTotal(){
                    const qty = parseInt(qtyInput.value) || 0;
                    const total = unitPrice * qty;
                    totalInput.value = formatRupiah(total);
                    if(totalHidden) totalHidden.value = total;
                }

                qtyInput.addEventListener('input', updateTotal);
                updateTotal();
            });
        </script>
    </div>
</div>
@endsection