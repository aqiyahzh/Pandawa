@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Pengaturan Halaman Tentang USER</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:underline">Kembali</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">{{ session('error') }}</div>
    @endif 

    {{-- ACTION HARUS SAMA DENGAN ROUTE UPDATE --}}
    <form action="{{ route('admin.settings.aboutpage.update') }}" method="POST" id="aboutPageForm" enctype="multipart/form-data">
        @csrf

        {{-- TITLE --}}
        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Judul</label>
            <textarea name="about_Title" rows="2" class="w-full p-3 border rounded">{{ $aboutTitle ?? '' }}</textarea>
        </div>

        {{-- SUBTITLE --}}
        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Subjudul</label>
            <textarea name="about_Subtitle" rows="4" class="w-full p-3 border rounded">{{ $aboutSubtitle ?? '' }}</textarea>
        </div>

        {{-- EVENT DESCRIPTIONS --}}
        <div class="mb-6 grid md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium mb-2">Deskripsi Event 1</label>
                <textarea name="about_Event1" rows="3" class="w-full p-3 border rounded">{{ $aboutEvent1 ?? '' }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Deskripsi Event 2</label>
                <textarea name="about_Event2" rows="3" class="w-full p-3 border rounded">{{ $aboutEvent2 ?? '' }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Deskripsi Event 3</label>
                <textarea name="about_Event3" rows="3" class="w-full p-3 border rounded">{{ $aboutEvent3 ?? '' }}</textarea>
            </div>
        </div>

        {{-- EVENT SUBDESCRIPTIONS --}}
        <div class="mb-6 grid md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium mb-2">Subdeskripsi Event 1</label>
                <textarea name="about_EventSub1" rows="3" class="w-full p-3 border rounded">{{ $aboutEventSub1 ?? '' }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Subdeskripsi Event 2</label>
                <textarea name="about_EventSub2" rows="3" class="w-full p-3 border rounded">{{ $aboutEventSub2 ?? '' }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Subdeskripsi Event 3</label>
                <textarea name="about_EventSub3" rows="3" class="w-full p-3 border rounded">{{ $aboutEventSub3 ?? '' }}</textarea>
            </div>
        </div>

        {{-- MAIN TEXTS --}}
        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Teks Utama 1</label>
            <textarea name="about_Text1" rows="5" class="w-full p-3 border rounded">{{ $aboutText1 ?? '' }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Teks Utama 2</label>
            <textarea name="about_Text2" rows="5" class="w-full p-3 border rounded">{{ $aboutText2 ?? '' }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Teks Utama 3</label>
            <textarea name="about_Text3" rows="5" class="w-full p-3 border rounded">{{ $aboutText3 ?? '' }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Teks Utama 4</label>
            <textarea name="about_Text4" rows="5" class="w-full p-3 border rounded">{{ $aboutText4 ?? '' }}</textarea>
        </div>

        {{-- PANEL IMAGES --}}
        <div class="mb-6 grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-2">Gambar Panel 1</label>
                <input type="file" name="panelImage1" class="w-full p-3 border rounded" />
                @if($panelImage1)
                    <img src="{{ asset($panelImage1) }}" alt="Panel Image 1" class="mt-4 max-h-40">
                    <div class="mt-2">
                        <label class="inline-flex items-center text-sm">
                            <input type="checkbox" name="remove_panel1" class="form-checkbox h-4 w-4 text-yellow-500">
                            <span class="ml-2 text-gray-600">Hapus gambar saat ini</span>
                        </label>
                    </div>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Gambar Panel 2</label>
                <input type="file" name="panelImage2" class="w-full p-3 border rounded" />
                @if($panelImage2)
                    <img src="{{ asset($panelImage2) }}" alt="Panel Image 2" class="mt-4 max-h-40">
                    <div class="mt-2">
                        <label class="inline-flex items-center text-sm">
                            <input type="checkbox" name="remove_panel2" class="form-checkbox h-4 w-4 text-yellow-500">
                            <span class="ml-2 text-gray-600">Hapus gambar saat ini</span>
                        </label>
                    </div>
                @endif
            </div>
        </div>

        {{-- TIMELINE (repeatable inputs, serialized to timeline_json) --}}
        @php
            $timeline = [];
            if (!empty($timelineRaw)) {
                if (is_array($timelineRaw)) {
                    $timeline = $timelineRaw;
                } else {
                    $decoded = json_decode($timelineRaw, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) $timeline = $decoded;
                }
            }
        @endphp

        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium">Timeline</label>
                <button type="button" id="addTimelineBtn" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Tambah Item</button>
            </div>

            <div id="timelineList" class="space-y-3">
                @forelse($timeline as $t)
                <div class="timeline-row p-3 border rounded bg-white">
                    <div class="grid grid-cols-12 gap-2 items-center">
                        <input type="text" name="timeline_date[]" placeholder="Tanggal" value="{{ $t['date'] ?? '' }}" class="col-span-3 p-2 border rounded">
                        <input type="text" name="timeline_title[]" placeholder="Judul" value="{{ $t['title'] ?? '' }}" class="col-span-4 p-2 border rounded">
                        <input type="text" name="timeline_desc[]" placeholder="Deskripsi singkat" value="{{ $t['desc'] ?? '' }}" class="col-span-4 p-2 border rounded">
                        <button type="button" class="removeTimelineBtn col-span-1 text-red-600">Hapus</button>
                    </div>
                </div>
                @empty
                <div class="timeline-row p-3 border rounded bg-white">
                    <div class="grid grid-cols-12 gap-2 items-center">
                        <input type="text" name="timeline_date[]" placeholder="Tanggal" class="col-span-3 p-2 border rounded">
                        <input type="text" name="timeline_title[]" placeholder="Judul" class="col-span-4 p-2 border rounded">
                        <input type="text" name="timeline_desc[]" placeholder="Deskripsi singkat" class="col-span-4 p-2 border rounded">
                        <button type="button" class="removeTimelineBtn col-span-1 text-red-600">Hapus</button>
                    </div>
                </div>
                @endforelse
            </div>

            <input type="hidden" name="timeline_json" id="timelineJson">
            <p class="text-sm text-gray-500 mt-2">Isi timeline dengan baris terpisah; sistem akan menyimpan data sebagai struktur yang bisa ditampilkan di halaman publik.</p>
        </div>
        

        <button type="submit"
            class="bg-yellow-500 text-white font-bold px-6 py-3 rounded hover:bg-yellow-600 transition">
            Simpan Perubahan
        </button>

    </form>
</div>

@endsection
@section('scripts')
<script>
    // Timeline repeatable UI: add/remove rows and serialize to hidden JSON before submit
    (function(){
        const addBtn = document.getElementById('addTimelineBtn');
        const list = document.getElementById('timelineList');
        const form = document.getElementById('aboutPageForm');
        const timelineJson = document.getElementById('timelineJson');

        function bindRemove(btn){
            btn.addEventListener('click', (e)=>{
                const row = e.target.closest('.timeline-row');
                if(row) row.remove();
            });
        }

        document.querySelectorAll('.removeTimelineBtn').forEach(bindRemove);

        addBtn.addEventListener('click', ()=>{
            const row = document.createElement('div');
            row.className = 'timeline-row p-3 border rounded bg-white';
            row.innerHTML = `
                <div class="grid grid-cols-12 gap-2 items-center">
                    <input type="text" name="timeline_date[]" placeholder="Tanggal" class="col-span-3 p-2 border rounded">
                    <input type="text" name="timeline_title[]" placeholder="Judul" class="col-span-4 p-2 border rounded">
                    <input type="text" name="timeline_desc[]" placeholder="Deskripsi singkat" class="col-span-4 p-2 border rounded">
                    <button type="button" class="removeTimelineBtn col-span-1 text-red-600">Hapus</button>
                </div>
            `;
            list.appendChild(row);
            bindRemove(row.querySelector('.removeTimelineBtn'));
        });

        form.addEventListener('submit', ()=>{
            const items = [];
            const rows = list.querySelectorAll('.timeline-row');
            rows.forEach(r => {
                const date = r.querySelector('input[name="timeline_date[]"]').value.trim();
                const title = r.querySelector('input[name="timeline_title[]"]').value.trim();
                const desc = r.querySelector('input[name="timeline_desc[]"]').value.trim();
                if(date || title || desc) items.push({date, title, desc});
            });
            timelineJson.value = JSON.stringify(items);
        });
    })();
</script>

@endsection