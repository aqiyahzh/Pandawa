@extends('layouts.app')

@section('content')

@php
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

// Hero settings (keberadaan hero image optional)
$heroTitle = $getSetting('home_hero_title', 'PANDAWA SMALA');
$heroSubtitle = $getSetting('home_hero_subtitle', 'Suporter Resmi SMA 5');
$heroSubtext = $getSetting('home_hero_subtext', 'Bersatu dalam semangat, bergerak dalam kebersamaan. Kami adalah suara yang menggema di setiap pertandingan!');
$heroTagline = $getSetting('home_hero_tagline', 'Satu Jiwa, Satu Suara, Satu Kebanggaan');
$heroCtaText = $getSetting('home_hero_cta_text', 'Kenali Kami Lebih Dekat');
$heroCtaUrl = $getSetting('home_hero_cta_url', '#about');
// Hero images: stored as JSON array in setting 'home_hero_image'
$heroImageRaw = $getSetting('home_hero_image', '[]');
$heroImages = [];
if (is_array($heroImageRaw)) {
$heroImages = $heroImageRaw;
} elseif (is_string($heroImageRaw)) {
$decoded = json_decode($heroImageRaw, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) $heroImages = $decoded;
}

// Stats (individual keys)
$statsMembers = (int) ($getSetting('home_stats_members', 500));
$statsYears = (int) ($getSetting('home_stats_years', 7));
$statsMatches = (int) ($getSetting('home_stats_matches', 100));
$statsAwards = (int) ($getSetting('home_stats_awards', 50));

// About structured fields (these are the new fields editable via admin)
$aboutVisionText = $getSetting('about_vision', 'Menjadi supporter terbaik yang memberikan dukungan moral dan semangat kepada atlet SMA 5 dalam setiap kompetisi.');
$aboutMissionText = $getSetting('about_mission', 'Membangun solidaritas, sportivitas, dan kebersamaan di antara siswa SMA 5 melalui dukungan yang positif dan energik.');
$aboutValuesText = $getSetting('about_values', 'Loyalitas, Kebersamaan, Sportivitas, dan Semangat Pantang Menyerah adalah fondasi yang membuat Pandawa tetap kuat.');

// FAQ
$homeFaqRaw = $getSetting('home_faq', '[]');
$faqItems = [];
if (is_array($homeFaqRaw)) {
$faqItems = $homeFaqRaw;
} elseif (is_string($homeFaqRaw)) {
$decoded = json_decode($homeFaqRaw, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) $faqItems = $decoded;
}

// Gallery
$galleryRaw = $getSetting('home_gallery_images', '[]');
$galleryImages = [];
if (is_array($galleryRaw)) {
$galleryImages = $galleryRaw;
} elseif (is_string($galleryRaw)) {
$decoded = json_decode($galleryRaw, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) $galleryImages = $decoded;
}
@endphp

<!-- Hero Section -->
<section id="home" class="relative w-full h-[85vh] md:h-[95vh] overflow-hidden">

    <div class="swiper hero-swiper w-full h-full">
        <div class="swiper-wrapper h-full">

            @if(!empty($heroImages) && count($heroImages))
            @foreach($heroImages as $img)
            <div class="swiper-slide relative w-full h-full">
                <img
                    src="{{ asset($img) }}"
                    alt="Hero Image"
                    class="w-full h-full object-cover object-center">
            </div>
            @endforeach
            @else
            <div class="swiper-slide relative w-full h-full">
                <img
                    src="{{ asset('images/hero-default.jpg') }}"
                    alt="Hero Image"
                    class="w-full h-full object-cover object-center">
            </div>
            @endif

        </div>

        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev text-white"></div>
        <div class="swiper-button-next text-white"></div>
    </div>

    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black/50 z-10"></div>

    <!-- Text Overlay -->
    <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 z-20">

        <!-- Title -->
        <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-extrabold text-white mb-5 drop-shadow-2xl font-poppins leading-snugged">
            {!! $heroTitle !!}
        </h1>

        <!-- Subtitle -->
        <p class="text-2xl sm:text-3xl md:text-4xl text-yellow-400 mb-4 drop-shadow-xl font-poppins">
            {!! $heroSubtitle !!}
        </p>

        <!-- Description -->
        <p class="text-lg sm:text-xl md:text-2xl text-gray-200 max-w-3xl mx-auto mb-6 drop-shadow font-poppins">
            {!! $heroSubtext !!}
        </p>

        <!-- Tagline -->
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-8 drop-shadow-2xl font-poppins">
            {!! $heroTagline !!}
        </h2>

        <!-- CTA -->
        <a href="{{ $heroCtaUrl }}"
            class="inline-block gradient-gold text-black font-bold px-10 py-5 rounded-full text-lg md:text-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 font-poppins">
            {{ $heroCtaText }}
        </a>

    </div>


</section>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined') {
            new Swiper('.hero-swiper', {
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false
                },
                pagination: {
                    el: '.hero-swiper .swiper-pagination',
                    clickable: true
                },
                navigation: {
                    nextEl: '.hero-swiper .swiper-button-next',
                    prevEl: '.hero-swiper .swiper-button-prev'
                },
            });
        }
    });
</script>

<!-- Stats Section -->
<section class="p-16 bg-gray-50" id="stats-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">

            <!-- Anggota Aktif -->
            <div class="text-center section-visible">
                <div class="flex justify-center items-center gap-2 mb-2">
                    <i class="bi bi-people-fill text-yellow-500 text-3xl"></i>
                    <div class="stats-number text-gradient counter"
                        data-target="{{ $statsMembers }}"
                        data-suffix="+">0</div>
                </div>
                <p class="text-gray-600 font-semibold mt-2">Anggota Aktif</p>
            </div>

            <!-- Tahun Berdiri -->
            <div class="text-center section-visible">
                <div class="flex justify-center items-center gap-2 mb-2">
                    <i class="bi bi-calendar-event-fill text-yellow-500 text-3xl"></i>
                    <div class="stats-number text-gradient counter"
                        data-target="{{ $statsYears }}"
                        data-suffix="">{{ $statsYears }}</div>
                </div>
                <p class="text-gray-600 font-semibold mt-2">Tahun Berdiri</p>
            </div>

            <!-- Pertandingan -->
            <div class="text-center section-visible">
                <div class="flex justify-center items-center gap-2 mb-2">
                    <i class="bi bi-trophy-fill text-yellow-500 text-3xl"></i>
                    <div class="stats-number text-gradient counter"
                        data-target="{{ $statsMatches }}"
                        data-suffix="+">0</div>
                </div>
                <p class="text-gray-600 font-semibold mt-2">Pertandingan</p>
            </div>

            <!-- Prestasi -->
            <div class="text-center section-visible">
                <div class="flex justify-center items-center gap-2 mb-2">
                    <i class="bi bi-award-fill text-yellow-500 text-3xl"></i>
                    <div class="stats-number text-gradient counter"
                        data-target="{{ $statsAwards }}"
                        data-suffix="+">0</div>
                </div>
                <p class="text-gray-600 font-semibold mt-2">Prestasi</p>
            </div>

        </div>

    </div>
</section>

<script>
    (function() {
        let counterStarted = false;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !counterStarted) {
                    counterStarted = true;
                    document.querySelectorAll('.counter').forEach(counter => {
                        const target = parseInt(counter.getAttribute('data-target')) || 0;
                        const suffix = counter.getAttribute('data-suffix') || '';
                        const duration = 2000;
                        const steps = 60;
                        const stepDuration = duration / steps;
                        const increment = target / steps;
                        let current = 0,
                            step = 0;
                        const timer = setInterval(() => {
                            step++;
                            current += increment;
                            if (step >= steps) {
                                counter.textContent = target + suffix;
                                clearInterval(timer);
                            } else {
                                counter.textContent = Math.floor(current) + suffix;
                            }
                        }, stepDuration);
                    });
                }
            });
        }, {
            threshold: 0.5
        });
        const el = document.querySelector('#stats-section');
        if (el) observer.observe(el);
    })();
</script>

<!-- About Section (Vision / Mission / Values) -->
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 section-visible">
            <h2 id="about-title" class="text-5xl font-bold text-gradient mb-4">Visi & Misi Pandawa</h2>
            <div class="w-24 h-1 gradient-gold mx-auto"></div>
        </div>

        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="section-visible">
                <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-3xl p-10 shadow-2xl">
                    <div class="text-6xl text-center mb-6"><i class="bi bi-trophy-fill"></i></div>
                    <h3 class="text-3xl font-bold text-black text-center mb-4">Semangat Juara</h3>
                    <p class="text-black text-center text-lg">Pandawa adalah lebih dari sekadar supporter. Kami adalah keluarga besar yang bersatu dalam satu tujuan: mendukung SMA 5 dengan sepenuh hati!</p>
                </div>
            </div>

            <div class="section-visible space-y-6">
                <div class="bg-gray-50 p-6 rounded-xl border-l-4 border-yellow-500 card-hover">
                    <h4 class="text-xl font-bold text-gray-800 mb-2"><i class="bi bi-award-fill"></i>Visi Kami</h4>
                    <p class="text-gray-700">{!! nl2br(e($aboutVisionText)) !!}</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl border-l-4 border-black card-hover">
                    <h4 class="text-xl font-bold text-gray-800 mb-2"><i class="bi bi-hand-index-fill"></i>Misi Kami</h4>
                    <p class="text-gray-700">{!! nl2br(e($aboutMissionText)) !!}</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl border-l-4 border-yellow-500 card-hover">
                    <h4 class="text-xl font-bold text-gray-800 mb-2"><i class="bi bi-heart-pulse-fill"></i>Nilai Kami</h4>
                    <p class="text-gray-700">{!! nl2br(e($aboutValuesText)) !!}</p>
                </div>
            </div>
        </div>

        <!-- Tombol ke halaman about -->
        <div class="mt-8 text-center">
            <a href="{{ url('/about') }}" class="inline-block bg-yellow-600 text-white font-semibold px-6 py-3 rounded-full hover:bg-yellow-700 transition duration-300">
                Selengkapnya Tentang Kami
            </a>
        </div>
    </div>
</section>

<!-- Best Seller Products (keberadaan data fallback ke sample ids) -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 section-visible">
            <h2 class="text-5xl font-bold text-gradient mb-4">Best Seller Products</h2>
            <p class="text-gray-600 text-lg mb-4">Produk pilihan yang paling sering dibeli oleh pelanggan setia kami</p>
            <div class="w-24 h-1 gradient-gold mx-auto"></div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @php
            $bestSellers = \App\Models\Merchandise::whereIn('id', [1,2,4])->get();
            @endphp

            @forelse($bestSellers as $product)
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:-translate-y-2 transition-all duration-300 flex flex-col section-visible">
                <div class="h-64 w-full overflow-hidden flex-shrink-0">
                    @php
                    $thumb = $product->gambar_utama ? $product->gambar_utama : ($product->images->count() ? $product->images[0]->image : null);
                    @endphp
                    @if ($thumb)
                    <img src="{{ asset('uploads/merch/'.$thumb) }}" alt="{{ $product->nama_barang }}" class="w-full h-full object-cover">
                    @else
                    <img src="{{ asset('noimage.png') }}" class="w-full h-full object-cover">
                    @endif
                </div>

                <div class="p-6 flex flex-col flex-grow">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $product->nama_barang }}</h3>
                    <p class="text-gray-600 text-sm mb-4 flex-grow">{{ Str::limit($product->deskripsi, 80) }}</p>

                    <div class="flex justify-between items-center mt-auto pt-4 border-t border-gray-200">
                        <span class="text-xl font-bold text-gradient">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                        <a href="{{ route('merchdetail', $product->id) }}" class="bg-yellow-500 text-black font-bold px-4 py-2 rounded-full hover:shadow-lg transition-all text-sm">Lihat</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-8">
                <p class="text-gray-600">Produk belum tersedia</p>
            </div>
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ url('/merchandise') }}" class="inline-block gradient-gold text-black font-bold px-8 py-4 rounded-full hover:shadow-2xl transition-all duration-300 transform hover:scale-105">Lihat Semua Merchandise</a>
        </div>
    </div>
</section>

<!-- Gallery -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 section-visible">
            <h2 class="text-5xl font-bold text-gradient mb-4">Our Memories</h2>
            <p class="text-gray-600 text-lg mb-4">Koleksi foto dan video momen terbaik kami di tribun dan pertandingan — kenangan yang selalu kami abadikan.</p>
            <div class="w-24 h-1 gradient-gold mx-auto"></div>
        </div>

        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="section-visible">
                <div class="relative bg-gray-900 rounded-2xl overflow-hidden shadow-2xl">
                    <div class="swiper gallery-swiper">
                        <div class="swiper-wrapper">
                                @php
                                    $defaultImgs = ['images/sup1.png','images/sup2.png','images/sup3.png'];
                                    $imgs = count($galleryImages) ? $galleryImages : $defaultImgs;
                                @endphp
                                @foreach($imgs as $image)
                            <div class="swiper-slide">
                                <img src="{{ asset($image) }}" alt="Pandawa Memory" class="w-full h-80 object-cover">
                            </div>
                            @endforeach
                        </div>

                        <div class="swiper-button-prev text-white"></div>
                        <div class="swiper-button-next text-white"></div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>

            <div class="section-visible">
                <div>
                    <h3 class="text-4xl font-bold text-gray-800 mb-6">Pandawa</h3>
                    <p class="text-gray-600 text-lg leading-relaxed mb-6">
                        {{ $getSetting('home_intro_text', 'Pandawa is supporter group from SMAN 5 Samarinda, this group start from 2017, they have slogan #StickTogether.') }}
                    </p>

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg mb-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-3"><i class="bi bi-camera-fill"></i> Kenangan Kami</h4>
                        <p class="text-gray-700">
                            {{ $getSetting('home_gallery_text', 'Setiap momen berharga diabadikan dengan penuh kebanggaan. Dari setiap pertandingan, event, hingga gathering bersama, kami selalu mengabadikan kenangan indah di tribun dan setiap sudut lapangan.') }}
                        </p>
                    </div>

                    <a href="{{ url('/gallery') }}" class="inline-block gradient-gold text-black font-bold px-8 py-4 rounded-full hover:shadow-2xl transition-all duration-300 transform hover:scale-105">Lihat Gallery →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined') {
            new Swiper('.gallery-swiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false
                },
                pagination: {
                    el: '.gallery-swiper .swiper-pagination',
                    clickable: true
                },
                navigation: {
                    nextEl: '.gallery-swiper .swiper-button-next',
                    prevEl: '.gallery-swiper .swiper-button-prev'
                },
            });
        }
    });
</script>

<!-- FAQ Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-10 items-start">
            <div class="pr-8">
                <h2 class="text-5xl font-bold text-gradient mb-4">FAQ?</h2>
                <div class="w-24 h-1 gradient-gold mb-6"></div>
                <p class="text-gray-600">Jika kawan - kawan sekalian memiliki pertanyaan, dapat menghubungi kami lewat WA ataupun lewat email kami di halaman contact!</p>
            </div>

            <div>
                <div class="space-y-4">
                    @if(count($faqItems))
                    @foreach($faqItems as $item)
                    <div class="faq-item bg-white rounded-lg shadow p-5">
                        <button class="faq-question w-full text-left flex items-center justify-between" aria-expanded="false">
                            <span class="font-semibold text-lg">{{ $item['q'] ?? '-' }}</span>
                            <span class="faq-toggle text-xl">+</span>
                        </button>
                        <div class="faq-answer mt-3 text-gray-600" style="max-height:0; overflow:hidden; transition:max-height 0.35s ease;">
                            {!! nl2br(e($item['a'] ?? '')) !!}
                        </div>
                    </div>
                    @endforeach
                    @else
                    {{-- fallback small static FAQ --}}
                    <div class="faq-item bg-white rounded-lg shadow p-5">
                        <button class="faq-question w-full text-left flex items-center justify-between" aria-expanded="false">
                            <span class="font-semibold text-lg">Apa itu Pandawa dan kapan berdiri?</span>
                            <span class="faq-toggle text-xl">+</span>
                        </button>
                        <div class="faq-answer mt-3 text-gray-600" style="max-height:0; overflow:hidden; transition:max-height 0.35s ease;">
                            Pandawa adalah komunitas supporter resmi SMAN 5 Samarinda yang berdiri pada 2017. Kami mendukung semua tim sekolah dan mengabadikan momen-momen di setiap pertandingan.
                        </div>
                    </div>
                    <div class="faq-item bg-white rounded-lg shadow p-5">
                        <button class="faq-question w-full text-left flex items-center justify-between" aria-expanded="false">
                            <span class="font-semibold text-lg">Cara beli merchandise gimana?</span>
                            <span class="faq-toggle text-xl">+</span>
                        </button>
                        <div class="faq-answer mt-3 text-gray-600" style="max-height:0; overflow:hidden; transition:max-height 0.35s ease;">
                            Kunjungi halaman Merchandise, pilih produk, lalu klik Pesan. Anda akan diarahkan ke detail produk untuk menyelesaikan pemesanan.
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(item => {
            const btn = item.querySelector('.faq-question');
            const ans = item.querySelector('.faq-answer');
            const toggle = item.querySelector('.faq-toggle');

            btn.addEventListener('click', () => {
                const expanded = btn.getAttribute('aria-expanded') === 'true';

                // close others
                faqItems.forEach(other => {
                    if (other !== item) {
                        other.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
                        other.querySelector('.faq-answer').style.maxHeight = '0';
                        other.querySelector('.faq-toggle').textContent = '+';
                    }
                });

                if (expanded) {
                    btn.setAttribute('aria-expanded', 'false');
                    ans.style.maxHeight = '0';
                    toggle.textContent = '+';
                } else {
                    btn.setAttribute('aria-expanded', 'true');
                    ans.style.maxHeight = ans.scrollHeight + 'px';
                    toggle.textContent = '×';
                }
            });
        });
    });
</script>

@endsection