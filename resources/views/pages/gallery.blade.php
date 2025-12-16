@extends('layouts.app')

@section('content')

<!-- Gallery Hero -->
<section class="pt-40 md:pt-52 pb-12 bg-gradient-to-b from-yellow-50 via-white to-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-10 md:mb-14">
            <h1 class="text-6xl md:text-7xl font-extrabold text-yellow-500 leading-tight">Galeri Pandawa</h1>
            <p class="mt-6 text-lg md:text-xl text-gray-600 max-w-4xl mx-auto">Koleksi foto dan video momen terbaik Pandawa — dari banner besar di lapangan sampai aksi supporter yang bersemangat.</p>
        </div>

        <div class="flex items-center justify-center gap-4 mb-8">
            <a href="#photos" class="px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg">Lihat Foto</a>
            <a href="#videos" class="px-6 py-3 border border-yellow-500 text-yellow-600 rounded-lg">Tonton Video</a>
            <a href="#contact" class="px-4 py-2 text-sm text-gray-600 underline">Hubungi Kami</a>
        </div>
    </div>
</section>

<!-- Photo Gallery -->
<section id="photos" class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Foto Kenangan</h2>
            <p class="text-gray-600 mt-2">Scroll untuk melihat momen-momen terbaik kami. Klik gambar untuk melihat ukuran penuh.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @php
                use App\Models\Setting;

                $raw = Setting::where('key', 'pandagaleri')->value('value') ?? '[]';
                $items = [];
                if (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) $items = $decoded;
                } elseif (is_array($raw)) {
                    $items = $raw;
                }

                $photos = array_values(array_filter($items, function($it){ return ($it['type'] ?? '') === 'image'; }));
            @endphp

            @if(count($photos) === 0)
                <p class="text-gray-500">Belum ada foto di galeri.</p>
            @endif

            @foreach($photos as $it)
                <a href="{{ asset($it['path']) }}" target="_blank" class="group block rounded-xl overflow-hidden shadow hover:shadow-xl transition">
                    <div class="w-full h-48 md:h-56 lg:h-64 bg-gray-100 flex items-center justify-center">
                        <img src="{{ asset($it['path']) }}" alt="galeri" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Video Section -->
<section id="videos" class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800">Video Highlights</h2>
            <p class="text-gray-600 mt-2">Tonton video acara dan highlights kami langsung dari YouTube.</p>
        </div>

        @php
            $videos = array_values(array_filter($items, function($it){ return ($it['type'] ?? '') === 'video'; }));
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if(count($videos) === 0)
                <p class="text-gray-500">Belum ada video di galeri.</p>
            @endif

            @foreach($videos as $v)
            <div class="w-full rounded-xl overflow-hidden shadow-lg bg-white">
                <div style="position:relative;padding-top:56.25%;">
                    @php
                        $iframe = $v['iframe'] ?? '';
                        // sanitize: remove width/height/style attributes and inject responsive attributes
                        $iframe = preg_replace('/\s(width|height)="[^"]*"/i', '', $iframe);
                        $iframe = preg_replace('/\sstyle="[^"]*"/i', '', $iframe);
                        // ensure iframe has responsive absolute styling
                        $iframe = preg_replace('/<iframe/i', '<iframe style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" loading="lazy"', $iframe, 1);
                        echo $iframe;
                    @endphp
                </div>
                <div class="p-4">
                    {{-- Optional caption could be added in future --}}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>