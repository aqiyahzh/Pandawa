
@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Kelola Galeri (Foto & Video)</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:underline">Kembali</a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.settings.admingaleri.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Gambar/Video Galeri</label>

            <p class="text-xs text-gray-500 mb-2">Gambar disimpan di <code>public/pandagaleri/</code>. Anda dapat mengunggah beberapa gambar sekaligus dan menambahkan beberapa video (iframe YouTube).</p>

            <div id="existingGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-6">
                @foreach($items as $it)
                <div class="relative bg-white rounded-2xl shadow-md p-4 existing-item" data-item='{{ json_encode($it) }}'>
                    {{-- Image card --}}
                    @if(($it['type'] ?? '') === 'image')
                    <div class="overflow-hidden rounded-xl bg-gray-100 mb-4" style="height:160px;">
                        <img src="{{ asset($it['path']) }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700 font-medium">Foto</span>
                    </div>
                    <button type="button" class="removeBtn absolute right-3 bottom-3 bg-red-600 hover:bg-red-700 text-white rounded-full p-2 shadow-md" title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V4a1 1 0 011-1h6a1 1 0 011 1v3"/></svg>
                    </button>

                    @elseif(($it['type'] ?? '') === 'video')
                    {{-- Video card: preview above, editable iframe textarea below --}}
                    <div class="mb-3 rounded-lg overflow-hidden bg-black" style="position:relative;padding-top:56.25%;">
                        @php
                            $preview = $it['iframe'] ?? '';
                            $preview = preg_replace('/\s(width|height)="[^"]*"/i', '', $preview);
                            $preview = preg_replace('/\sstyle="[^"]*"/i', '', $preview);
                            $preview = preg_replace('/<iframe/i', '<iframe style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" loading="lazy"', $preview, 1);
                            echo $preview;
                        @endphp
                    </div>

                    <label class="block text-sm font-medium mb-1">Iframe video (editable)</label>
                    <textarea class="existingVideoTextarea w-full p-2 border rounded mb-4" rows="3">{{ $it['iframe'] }}</textarea>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700 font-medium">Video</span>
                    </div>
                    <button type="button" class="removeBtn absolute right-3 bottom-3 bg-red-600 hover:bg-red-700 text-white rounded-full p-2 shadow-md" title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V4a1 1 0 011-1h6a1 1 0 011 1v3"/></svg>
                    </button>
                    @endif
                </div>
                @endforeach
            </div>

            <label class="block mb-2 text-sm">Tambah Gambar</label>
            <div class="border rounded-lg p-3 mb-4 bg-white">
                <input type="file" name="gallery_images[]" id="galleryUpload" multiple accept="image/*" class="w-full">
            </div>

            <p class="text-sm font-medium mt-2 mb-2">Tambah Video (iframe)</p>
            <div id="videoList" class="space-y-3 mb-3 border rounded-lg p-3 bg-white">
                {{-- existing video fields will be added by JS if present --}}
            </div>
            <button type="button" id="addVideoBtn" class="bg-yellow-500 text-white px-4 py-2 rounded-lg font-medium">Tambah Video</button>

            <input type="hidden" name="existing_items_json" id="existingItemsJson" />
        </div>

        <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded font-semibold mt-4">Simpan</button>
    </form>
</div>


<script>
        document.addEventListener('DOMContentLoaded', function () {
        const existingGrid = document.getElementById('existingGrid');
        const existingItemsJson = document.getElementById('existingItemsJson');
        const addVideoBtn = document.getElementById('addVideoBtn');
        const videoList = document.getElementById('videoList');

        // build kept list from DOM, attach __uid and mark element with data-uid
        let kept = [];
        existingGrid.querySelectorAll('.existing-item').forEach(el => {
            try {
                const it = JSON.parse(el.getAttribute('data-item'));
                const uid = Date.now().toString(36) + Math.random().toString(36).slice(2);
                it.__uid = uid;
                el.setAttribute('data-uid', uid);
                kept.push(it);
                // if there's a textarea for existing video, bind change to update kept
                const ta = el.querySelector('.existingVideoTextarea');
                if (ta) {
                    ta.addEventListener('input', () => {
                        const idx = kept.findIndex(k => k.__uid === uid);
                        if (idx !== -1) kept[idx].iframe = ta.value;
                        refreshHidden();
                    });
                }
            } catch (e) {}
        });

        function refreshHidden() {
            // strip internal keys before sending
            const out = kept.map(k => {
                if ((k.type || k.type === 'image') && k.type === 'image') return { type: 'image', path: k.path };
                if ((k.type || k.type === 'video') && k.type === 'video') return { type: 'video', iframe: k.iframe };
                return null;
            }).filter(Boolean);
            existingItemsJson.value = JSON.stringify(out);
        }

        // bind remove buttons
        function bindRemoves() {
            existingGrid.querySelectorAll('.removeBtn').forEach(btn => {
                btn.onclick = function (e) {
                    const wrap = e.target.closest('.existing-item');
                    if (!wrap) return;
                    const uid = wrap.getAttribute('data-uid');
                    if (uid) {
                        const idx = kept.findIndex(k => k.__uid === uid);
                        if (idx !== -1) kept.splice(idx, 1);
                    }
                    wrap.remove();
                    refreshHidden();
                }
            });
        }

        bindRemoves();
        refreshHidden();

        addVideoBtn.onclick = () => {
            const idx = videoList.children.length;
            const row = document.createElement('div');
            row.className = 'p-3 border rounded bg-white';
            row.innerHTML = `
                <label class="block text-xs font-medium mb-1">Iframe video (YouTube)</label>
                <textarea name="video_iframes[]" rows="3" class="w-full p-2 border rounded" placeholder="&lt;iframe src=...&gt;&lt;/iframe&gt;"></textarea>
                <div class="mt-2 text-right">
                    <button type="button" class="removeVideoBtn text-red-600 text-sm">Hapus</button>
                </div>
            `;
            videoList.appendChild(row);
            row.querySelector('.removeVideoBtn').onclick = () => row.remove();
        };
    });
</script>

@endsection
