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

//About Settings 
$aboutTitle = $getSetting('about_Title', 'Tentang Pandawa');
$aboutSubtitle = $getSetting('about_Subtitle', 'Mengenal lebih dekat tentang perjalanan, visi, dan karya-karya legendaris dari komunitas Pandawa — dari awal berdiri hingga karya-karya inspiratif kami.');
$aboutEvent1 = $getSetting('about_Event1', '12 Des 2025 — DBL Samarinda');
$aboutEvent2 = $getSetting('about_Event2', '20 Jan 2026 — Workshop Desain.');
$aboutEvent3 = $getSetting('about_Event3', 'TBA — Night Rally.');
$aboutEventSub1 = $getSetting('about_EventSub1', 'Pameran banner dan aksi dukungan langsung di lapangan.');
$aboutEventSub2 = $getSetting('about_EventSub2', 'Kelas desain komunitas untuk anggota baru dan lama.');
$aboutEventSub3 = $getSetting('about_EventSub3', 'Rencana aksi dan event komunitas nanti malam.');
$aboutText1 = $getSetting('about_Text1', 'Pandawa adalah komunitas supporter resmi SMA 5 Samarinda yang berdedikasi untuk memberikan dukungan terbaik bagi tim olahraga sekolah kami. Sejak didirikan pada tahun 2018, Pandawa telah tumbuh menjadi salah satu kelompok supporter paling bersemangat dan kreatif di kota ini.');
$aboutText2 = $getSetting('about_Text2', 'Kami dikenal karena semangat kami yang tak tergoyahkan, kreativitas dalam mendukung tim, dan komitmen kami untuk membangun tradisi supporter yang positif dan inklusif. Melalui berbagai kegiatan, mulai dari pembuatan banner besar hingga aksi dukungan di lapangan, kami berusaha menciptakan atmosfer yang mendukung dan memotivasi tim kami untuk meraih prestasi terbaik.');
$aboutText3 = $getSetting('about_Text3', 'Lebih dari sekadar supporter, Pandawa adalah keluarga besar yang saling mendukung dan menginspirasi satu sama lain. Kami percaya bahwa dukungan kami tidak hanya memberikan semangat bagi tim, tetapi juga mempererat ikatan di antara anggota komunitas kami. Bergabunglah dengan kami dalam perjalanan ini, dan mari kita ciptakan sejarah bersama sebagai bagian dari komunitas Pandawa yang penuh semangat dan dedikasi.');
$aboutText4 = $getSetting('about_Text4', 'Visi kami adalah menjadi komunitas supporter yang tidak hanya dikenal karena semangatnya, tetapi juga karena kontribusinya dalam membangun budaya olahraga yang positif dan inspiratif di lingkungan sekolah kami. Kami berkomitmen untuk terus berkembang, berinovasi, dan memberikan dukungan terbaik bagi tim olahraga SMA 5 Samarinda, sambil menjaga nilai-nilai kekeluargaan, kreativitas, dan inklusivitas yang menjadi dasar komunitas kami.');

// Panel Images: prefer stored settings (stored under public/aboutPanel).
// Do NOT fall back to bundled images here — only show uploaded images.
$panelImageSetting1 = $getSetting('panelImage1', null);
$panelImageSetting2 = $getSetting('panelImage2', null);

// if setting was stored as JSON array, pick the first image
if (is_array($panelImageSetting1)) {
    $panelImageSetting1 = $panelImageSetting1[0] ?? null;
}
if (is_array($panelImageSetting2)) {
    $panelImageSetting2 = $panelImageSetting2[0] ?? null;
}

// If a DB value exists, build URL from it; otherwise keep null so no image is rendered
$panelImage1 = $panelImageSetting1 ? asset(ltrim($panelImageSetting1, '/')) : null;
$panelImage2 = $panelImageSetting2 ? asset(ltrim($panelImageSetting2, '/')) : null;

/* TIMELINE: stored as JSON string in settings — decode to array for looping */
$timelineRaw = $getSetting('about_timeline', null);
$timeline = [];
if ($timelineRaw) {
    if (is_array($timelineRaw)) {
        $timeline = $timelineRaw;
    } else {
        $decoded = @json_decode($timelineRaw, true);
        if (is_array($decoded)) $timeline = $decoded;
    }
}

@endphp


<!-- Hero Section (enhanced) -->
<section class="pt-40 md:pt-52 pb-12 bg-gradient-to-b from-yellow-50 via-white to-white">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Top Title -->
        <div class="text-center mb-10 md:mb-14">
            <h1 class="text-7xl md:text-8xl font-extrabold text-yellow-500 leading-tight">{!! $aboutTitle !!}</h1>
            <!-- decorative strip removed as requested -->
            <p class="mt-6 text-lg md:text-xl text-gray-600 max-w-4xl mx-auto">{!! $aboutSubtitle !!}</p>
        </div>

        <!-- CTAs -->
        <div class="flex flex-col md:flex-row items-center justify-center gap-4 mb-10">
            <a href="/join" class="inline-block px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg shadow hover:shadow-md transition">Telusuri kami!</a>
            <a href="/merchandise" class="inline-block px-6 py-3 border border-yellow-500 text-yellow-600 font-semibold rounded-lg hover:bg-yellow-50 transition">Lihat Merchandise</a>
            <a href="#history" class="inline-block px-4 py-2 text-sm text-gray-600 underline">Lihat Sejarah</a>
        </div>

        <!-- Upcoming events strip -->
        <div class="bg-white rounded-xl shadow-inner p-4 mb-10">
            <h4 class="text-lg font-semibold text-gray-800 mb-3">Upcoming Events</h4>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 bg-gradient-to-r from-yellow-100 to-white p-4 rounded-lg border-l-4 border-yellow-400">
                    <div class="text-sm text-gray-700 font-semibold">{!! $aboutEvent1 !!}</div>
                    <div class="text-sm text-gray-600">{!! $aboutEventSub1 !!}</div>
                </div>
                <div class="flex-1 bg-white p-4 rounded-lg border border-gray-100">
                    <div class="text-sm text-gray-700 font-semibold">{!! $aboutEvent2 !!}</div>
                    <div class="text-sm text-gray-600">{!! $aboutEventSub2 !!}</div>
                </div>
                <div class="flex-1 bg-white p-4 rounded-lg border border-gray-100">
                    <div class="text-sm text-gray-700 font-semibold">{!! $aboutEvent3 !!}</div>
                    <div class="text-sm text-gray-600">{!! $aboutEventSub3 !!}</div>
                </div>
            </div>
        </div>

        <!-- Sponsors / partners row -->
        <div class="flex items-center justify-center gap-8 flex-wrap mb-8">
            <img src="{{ asset('images/sponsor1.png') }}" alt="sponsor1" class="h-20 md:h-24 opacity-100">
            <img src="{{ asset('images/sponsor2.png') }}" alt="sponsor2" class="h-20 md:h-24 opacity-100">
            <img src="{{ asset('images/sponsor3.png') }}" alt="sponsor3" class="h-20 md:h-24 opacity-100">
        </div>

        <!-- Short mission strip -->
        <div class="max-w-4xl mx-auto text-center text-gray-700">
            <p class="leading-relaxed">Pandawa berdiri untuk memperkuat semangat komunitas, mengekspresikan kreativitas, dan membangun tradisi dukungan yang tahan lama. Bergabunglah atau ikuti perkembangan karya kami di acara-acara mendatang.</p>
        </div>
    </div>
</section>

<!-- Panel Komik Pandawa -->
<section class="py-20 md:py-24 bg-white">
    <div class="max-w-6xl mx-auto px-4">

<!-- PANEL 1 -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex gap-8 items-start">
            <!-- GAMBAR KIRI -->
            @if($panelImage1)
            <div class="flex-shrink-0">
                <img src="{{ $panelImage1 }}" class="rounded-2xl shadow-xl w-[500px] object-cover" alt="pandawa">
            </div>
            @endif

            <!-- TEKS DI SAMPING -->
            <div class="flex-1">
                <p class="text-gray-700 leading-relaxed text-justify">
                    {!! $aboutText1 !!}
                    <br><br>
                    {!! $aboutText2 !!}
                </p>
            </div>
        </div>
    </div>
</section>


<!-- PANEL 2 -->
<section class="py-20">
        <div class="flex gap-8 items-start flex-row-reverse">
            <!-- GAMBAR KANAN -->
            @if($panelImage2)
            <div class="flex-shrink-0">
                <img src="{{ $panelImage2 }}" class="rounded-2xl shadow-xl w-[500px] object-cover" alt="pandawa">
            </div>
            @endif

            <!-- TEKS DI SAMPING -->
            <div class="flex-1">
                <p class="text-gray-700 leading-relaxed text-justify">
            {!! $aboutText3 !!}
            <br><br>
            {!! $aboutText4 !!}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Characters Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-5xl font-bold text-gradient mb-4">Kenali Kelima Pandawa</h2>
            <div class="w-24 h-1 gradient-gold mx-auto mb-6"></div>
            <p class="text-lg text-gray-600">Setiap karakter membawa nilai, semangat, dan identitas unik dalam komunitas Pandawa</p>
        </div>

        <!-- Characters Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- Yudhistira -->
            <div class="bg-gradient-to-br from-yellow-50 to-white rounded-xl shadow-lg hover:shadow-xl transition overflow-hidden border-l-4 border-yellow-500">
                <div class="h-40 overflow-hidden bg-gray-100">
                    <img src="{{ asset('images/yudhistira.jpg') }}" alt="Yudhistira" class="w-full h-full object-cover">
                </div>
                <div class="p-5">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Yudhistira</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Anak pertama dalam Pandawa Lima. Mempunyai nama lain yakni Puntadewa. Yudhistira sendiri mempunyai watak selalu bicara jujur dan tak pernah berbohong.</p>
                </div>
            </div>

            <!-- Bima -->
            <div class="bg-gradient-to-br from-yellow-50 to-white rounded-xl shadow-lg hover:shadow-xl transition overflow-hidden border-l-4 border-orange-500">
                <div class="h-40 overflow-hidden bg-gray-100">
                    <img src="{{ asset('images/bima.jpg') }}" alt="Bima" class="w-full h-full object-cover">
                </div>
                <div class="p-5">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Bima</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Bima dalam tokoh pewayangan Jawa. Bima juga sering disebut sebagai Werkudara. Bima ini pemberani, gagah permasa-meski demikian, Bima selalu sayang kepada adik-adiknya.</p>
                </div>
            </div>

            <!-- Arjuna -->
            <div class="bg-gradient-to-br from-yellow-50 to-white rounded-xl shadow-lg hover:shadow-xl transition overflow-hidden border-l-4 border-yellow-600">
                <div class="h-40 overflow-hidden bg-gray-100">
                    <img src="{{ asset('images/arjuna.jpg') }}" alt="Arjuna" class="w-full h-full object-cover">
                </div>
                <div class="p-5">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Arjuna</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Arjuna Pandawa yang ketiga dalam pewayangan Jawa. Arjuna mempunyai nama lain yakni Janaka atau Permadi.</p>
                </div>
            </div>

            <!-- Nakula -->
            <div class="bg-gradient-to-br from-yellow-50 to-white rounded-xl shadow-lg hover:shadow-xl transition overflow-hidden border-l-4 border-amber-500">
                <div class="h-40 overflow-hidden bg-gray-100">
                    <img src="{{ asset('images/nakula.jpg') }}" alt="Nakula" class="w-full h-full object-cover">
                </div>
                <div class="p-5">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Nakula</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Nakula sosok yang sangat menghibur hati, tetapi dalam bertugas, dan mahir dalam memainkan pedang.</p>
                </div>
            </div>

            <!-- Sadewa -->
            <div class="bg-gradient-to-br from-yellow-50 to-white rounded-xl shadow-lg hover:shadow-xl transition overflow-hidden border-l-4 border-yellow-700">
                <div class="h-40 overflow-hidden bg-gray-100">
                    <img src="{{ asset('images/sadewa.jpg') }}" alt="Sadewa" class="w-full h-full object-cover">
                </div>
                <div class="p-5">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Sadewa</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Sadewa juga ahli dalam perbintangan atau menghitung. Ramalannya selalu tepat dan dianggap mengetahui kejadian dimasa depan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TIMELINE -->
<section class="py-20 pb-40 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-5xl font-bold text-gradient mb-4">Sejarah Pandawa</h2>
            <div class="w-24 h-1 gradient-gold mx-auto mb-6"></div>
            <p class="text-lg text-gray-600">Perjalanan kami dari awal berdiri hingga kini</p>
        </div>

        <!-- Timeline Items (alternating layout with year marker) -->
        <div class="relative">
            <!-- central continuous line (only on md+ screens) -->
            <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 top-0 bottom-0 w-0.5 bg-yellow-300 z-10"></div>
            @foreach($timeline as $item)
                @php
                    // extract a 4-digit year if possible, otherwise fallback to original date
                    preg_match('/\d{4}/', $item['date'] ?? '', $m);
                    $year = $m[0] ?? ($item['date'] ?? '');
                    $isLeft = $loop->index % 2 === 0;
                @endphp

                <div class="mb-12 flex flex-col md:flex-row items-center w-full">
                    <div class="md:w-1/2 w-full md:pr-8 {{ $isLeft ? 'md:text-right' : 'hidden md:block' }}">
                        @if($isLeft)
                        <div class="inline-block bg-white p-6 rounded-lg shadow-lg border-l-4 border-yellow-400">
                            <h3 class="text-xl font-semibold text-gray-800">{{ $item['title'] }}</h3>
                            <p class="text-gray-600 mt-2">{{ $item['desc'] }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="md:w-0 md:flex-0 w-full flex items-center justify-start md:justify-center my-4 md:my-0 z-20">
                        <div class="relative flex items-center justify-center z-20">
                            <div class="w-12 h-12 md:w-16 md:h-16 bg-yellow-500 rounded-full text-white flex items-center justify-center font-bold text-sm md:text-lg shadow-lg border-4 border-white z-30">{{ $year }}</div>
                        </div>
                    </div>

                    <div class="md:w-1/2 w-full md:pl-8 {{ $isLeft ? 'hidden md:block' : '' }}">
                        @if(!$isLeft)
                        <div class="inline-block bg-white p-6 rounded-lg shadow-lg border-l-4 border-yellow-400 max-w-[560px]">
                            <h3 class="text-xl font-semibold text-gray-800">{{ $item['title'] }}</h3>
                            <p class="text-gray-600 mt-2">{{ $item['desc'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection