@extends('layouts.app')

@section('content')
@php
    $contactAddress = setting('contact_address', 'SMA 5, Jl. Juanda No. 5, Kota');
    $contactEmail = setting('contact_email', 'pandawa.smala@gmail.com');
    $contactPhone = setting('contact_phone', '+62 821-4961-1567');

    $mapIframe       = setting('contact_map_iframe', null);
        $mapHtml = null;
        if ($mapIframe) {
            // remove any width/height/style attributes to avoid fixed sizing
            $mapHtml = preg_replace('/\s(width|height|style)="[^"]*"/i', '', $mapIframe);
            // inject our responsive style and useful attributes
            $mapHtml = preg_replace(
                '/<iframe([^>]*)>/i',
                '<iframe$1 style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" loading="lazy" allowfullscreen>',
                $mapHtml
            );
        }

    // bentuk nomor WA
    $waNumber = preg_replace('/\D+/', '', $contactPhone);
@endphp

<section class="pt-40 md:pt-52 pb-12 bg-gradient-to-b from-yellow-50 via-white to-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-10 md:mb-14">
            <h1 class="text-6xl md:text-7xl font-extrabold text-yellow-500 leading-tight">Hubungi Kami</h1>
            <p class="mt-6 text-lg md:text-xl text-gray-600 max-w-4xl mx-auto">Punya pertanyaan atau ingin bergabung? Hubungi kami — hadir untuk membantu dan menerima ide-ide kreatif Anda.</p>
        </div>
        <div class="flex items-center justify-center gap-4 mb-8">
            <a href="#contact" class="px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg">Form Kontak</a>
            <a href="#map" class="px-6 py-3 border border-yellow-500 text-yellow-600 rounded-lg">Lihat Peta</a>
            <a href="https://wa.me/{{ preg_replace('/\\D+/', '', $contactPhone) }}" class="px-4 py-2 text-sm text-gray-600 underline" target="_blank">Hubungi via WA</a>
        </div>
    </div>
</section>

<!-- CONTACT SECTION -->
<section id="contact" class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- MAP -->
        <div id="map" class="mb-8 rounded-2xl overflow-hidden shadow-lg">
                @if ($mapHtml)
                    <div style="position:relative;padding-top:56.25%;min-height:320px;">
                        {!! $mapHtml !!}
                    </div>
                @endif
        </div>

        <div class="text-center mb-16 section-visible">
            <h2 class="text-5xl font-bold text-gradient mb-4">Kontak Kami</h2>
            <div class="w-24 h-1 gradient-gold mx-auto mb-6"></div>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Punya pertanyaan atau ingin bergabung? Jangan ragu untuk menghubungi kami!</p>
        </div>

        <div class="grid md:grid-cols-2 gap-12">
            <div class="section-visible">
                <div class="bg-gray-50 p-8 rounded-2xl">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Informasi Kontak</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 gradient-gold rounded-full flex items-center justify-center text-2xl flex-shrink-0">📍</div>
                            <div>
                                <h4 class="font-bold text-gray-800">Alamat</h4>
                                <p class="text-gray-600">{{ $contactAddress }}</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 gradient-gold rounded-full flex items-center justify-center text-2xl flex-shrink-0">📧</div>
                            <div>
                                <h4 class="font-bold text-gray-800">Email</h4>
                                <p class="text-gray-600">{{ $contactEmail }}</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 gradient-gold rounded-full flex items-center justify-center text-2xl flex-shrink-0">📱</div>
                            <div>
                                <h4 class="font-bold text-gray-800">Telepon</h4>
                                <p class="text-gray-600">
                                    <a href="https://wa.me/{{ preg_replace('/\\D+/', '', $contactPhone) }}" target="_blank" class="hover:underline">{{ $contactPhone }}</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="font-bold text-gray-800 mb-4">Jam Operasional</h4>
                        <p class="text-gray-600">Senin - Jumat: 15:00 - 18:00</p>
                        <p class="text-gray-600">Sabtu: 09:00 - 12:00</p>
                    </div>
                </div>
            </div>

            {{-- Non-functional contact form: tampilan tetap ada tetapi tidak submit --}}
            <div class="section-visible">
                {{-- onsubmit="return false" mencegah submit, dan tombol dibuat disabled --}}
                <form id="contact-form" class="bg-gray-50 p-8 rounded-2xl" method="POST" action="#" onsubmit="return false;" aria-disabled="true">
                    @csrf
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Kirim Pesan</h3>
                    <p class="text-sm text-gray-500 mb-4">Form ini hanya tampilan — fitur pengiriman pesan saat ini dinonaktifkan. Silakan hubungi kami langsung via WhatsApp atau email.</p>

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
                            <input type="text" id="name" name="name" placeholder="Contoh: Budi" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-yellow-500 focus:outline-none transition-colors" aria-disabled="true" disabled>
                        </div>

                        <div>
                            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" id="email" name="email" placeholder="contoh@domain.com" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-yellow-500 focus:outline-none transition-colors" aria-disabled="true" disabled>
                        </div>

                        <div>
                            <label for="subject" class="block text-gray-700 font-semibold mb-2">Subjek</label>
                            <input type="text" id="subject" name="subject" placeholder="Judul pesan" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-yellow-500 focus:outline-none transition-colors" aria-disabled="true" disabled>
                        </div>

                        <div>
                            <label for="message" class="block text-gray-700 font-semibold mb-2">Pesan</label>
                            <textarea id="message" name="message" rows="4" placeholder="Tulis pesan..." class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-yellow-500 focus:outline-none transition-colors resize-none" aria-disabled="true" disabled></textarea>
                        </div>

                        <button type="button" id="fakeSubmitBtn" class="w-full gradient-gold text-black font-bold py-4 rounded-lg hover:shadow-xl transition-all transform hover:scale-105" aria-disabled="true" disabled>
                            Kirim Pesan
                        </button>

                        <div id="contact-note" class="text-xs text-gray-500 mt-2">
                            Atau hubungi langsung via <a class="text-yellow-600 underline" href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a> atau <a class="text-yellow-600 underline" href="https://wa.me/{{ preg_replace('/\\D+/', '', $contactPhone) }}" target="_blank">WhatsApp</a>.
                        </div>
                    </div>
                </form>

                {{-- tempat menampilkan notifikasi singkat --}}
                <div id="contact-toast" class="hidden fixed bottom-8 right-8 bg-black text-white px-4 py-3 rounded-lg shadow-lg z-50"></div>
            </div>
        </div>
    </div>
</section>

<script>
    // Jika user klik tombol (yang disabled), kita tetap berikan feedback berupa toast agar tidak membingungkan.
    (function() {
        const fakeBtn = document.getElementById('fakeSubmitBtn');
        const toast = document.getElementById('contact-toast');

        if (!fakeBtn || !toast) return;

        fakeBtn.addEventListener('click', function (e) {
            showToast('Form saat ini non-aktif. Silakan hubungi via WhatsApp atau email.');
        });

        function showToast(message = '', timeout = 3500) {
            toast.textContent = message;
            toast.classList.remove('hidden');
            toast.classList.add('opacity-100');
            // simple fade timing
            setTimeout(() => {
                toast.classList.add('hidden');
            }, timeout);
        }
    })();
</script>
@endsection