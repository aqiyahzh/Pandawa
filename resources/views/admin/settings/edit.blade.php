@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Pengaturan Visi, Misi, Nilai & FAQ</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:underline">Kembali</a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm" enctype="multipart/form-data">
        @csrf

        {{-- VISI / MISI / NILAI --}}
        <div class="mb-6 grid md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium mb-2">Visi</label>
                <textarea name="about_vision" rows="5" class="w-full p-3 border rounded">{{ $aboutVision }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Misi</label>
                <textarea name="about_mission" rows="5" class="w-full p-3 border rounded">{{ $aboutMission }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Nilai</label>
                <textarea name="about_values" rows="5" class="w-full p-3 border rounded">{{ $aboutValues }}</textarea>
            </div>
        </div>

        {{-- HERO IMAGE --}}
        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Gambar Banner Home (maks 7 gambar)</label>
            <input type="file" name="home_hero_images[]" class="w-full p-3 border rounded" multiple accept="image/*">
            @if(!empty($heroImages) && count($heroImages))
            <p class="mt-2 text-sm text-gray-600">Gambar saat ini (centang untuk hapus):</p>
            <div class="grid grid-cols-3 gap-4 mt-2">
                @foreach($heroImages as $img)
                <div class="p-2 border rounded bg-white text-center">
                    <img src="{{ asset($img) }}" alt="Hero" class="mx-auto mb-2 max-h-32 object-contain">
                    <div class="flex items-center justify-center gap-2">
                        <label class="text-sm">
                            <input type="checkbox" name="remove_hero[]" value="{{ $img }}"> Hapus
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ORDER / PAYMENT SETTINGS --}}
        <div class="mb-6 bg-white border rounded p-4">
            <h2 class="text-lg font-semibold mb-3">Order & Pembayaran</h2>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nomor WhatsApp tujuan (untuk redirect setelah order)</label>
                <input type="text" name="order_whatsapp" class="w-full p-3 border rounded" value="{{ $orderWhatsapp ?? '' }}" placeholder="6281545063864">
                <p class="text-xs text-gray-500 mt-1">Bisa memasukkan +62, spaces atau 0. Contoh yang diterima: <code>+62 815-4506-3864</code> atau <code>081545063864</code>. Sistem akan menyimpan sebagai country code tanpa tanda: <code>6281545063864</code>.</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Instruksi Pembayaran / QRIS / Rekening (HTML diperbolehkan)</label>
                <textarea name="order_payment_instructions" rows="5" class="w-full p-3 border rounded">{{ $orderPaymentInstructions ?? '<label>Bukti Transfer : Transfer ke norek berikut - 17759807 BNI</label>' }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Bisa memasukkan teks, nomor rekening, atau HTML sederhana (mis. gambar QR).</p>
            </div>
        </div>


        {{-- FAQ --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <label class="font-medium text-sm">FAQ</label>
                <button type="button" id="addFaqBtn" class="bg-yellow-500 text-white px-3 py-1 rounded">
                    Tambah FAQ
                </button>
            </div>

            <div id="faqList" class="space-y-3">
                @forelse($faqItems as $f)
                <div class="faq-row p-4 border rounded bg-white">
                    <input type="text" class="faq-q w-full p-2 border rounded mb-2" placeholder="Pertanyaan"
                        value="{{ $f['q'] }}">
                    <textarea class="faq-a w-full p-2 border rounded mb-2" rows="3"
                        placeholder="Jawaban">{{ $f['a'] }}</textarea>
                    <button type="button" class="removeFaqBtn text-red-600 text-sm">Hapus</button>
                </div>
                @empty
                <div class="faq-row p-4 border rounded bg-white">
                    <input type="text" class="faq-q w-full p-2 border rounded mb-2" placeholder="Pertanyaan">
                    <textarea class="faq-a w-full p-2 border rounded mb-2" rows="3" placeholder="Jawaban"></textarea>
                    <button type="button" class="removeFaqBtn text-red-600 text-sm">Hapus</button>
                </div>
                @endforelse
            </div>

            {{--HERO SECTION--}}
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Judul Banner Home</label>
                <input type="text" name="home_hero_title"
                    class="w-full p-3 border rounded"
                    value="{{ $heroTitle?? '' }}">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Judul SUB HERO</label>
                <input type="text" name="home_hero_subtitle"
                    class="w-full p-3 border rounded"
                    value="{{ $heroSubtitle?? '' }}">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Hero text</label>
                <input type="text" name="home_hero_subtext"
                    class="w-full p-3 border rounded"
                    value="{{ $heroSubtext?? '' }}">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Hero tagline</label>
                <input type="text" name="home_hero_tagline"
                    class="w-full p-3 border rounded"
                    value="{{ $heroTagline?? '' }}">
            </div>

            {{-- Gallery management --}}
            <div>
                <label class="block text-sm font-medium mb-2">Gallery Images (upload multiple)</label>
                <p class="text-xs text-gray-500 mb-2">Upload gambar baru atau hapus gambar yang ada. File disimpan di <code>public/uploads/gallery/</code>.</p>

                {{-- existing gallery previews --}}
                <div id="existingGallery" class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                    @foreach($galleryImages as $img)
                    <div class="relative border rounded overflow-hidden" data-path="{{ $img }}">
                        <img src="{{ asset($img) }}" class="w-full h-32 object-cover">
                        <button type="button" class="absolute top-1 right-1 bg-white/90 text-red-600 rounded px-2 py-1 text-xs removeExistingBtn">Hapus</button>
                    </div>
                    @endforeach
                </div>

                <label class="block mb-2 text-sm">Tambah Gambar</label>
                <input type="file" name="gallery_images[]" id="galleryUpload" multiple accept="image/*" class="w-full p-3 border">

                <p class="text-xs text-gray-500 mb-2">Preview gambar yang akan diupload:</p>
                <div id="uploadPreview" class="grid grid-cols-2 md:grid-cols-4 gap-3"></div>

                {{-- Hidden field to store existing gallery paths that admin kept --}}
                <input type="hidden" name="existing_gallery_json" id="existingGalleryJson" />
            </div>


            <input type="hidden" name="home_faq_json" id="homeFaqJson">
        </div>

        <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded font-semibold">
            Simpan
        </button>
    </form>
</div>



<script>
    document.addEventListener("DOMContentLoaded", () => {
        const list = document.getElementById("faqList");
        const addBtn = document.getElementById("addFaqBtn");
        const form = document.getElementById("settingsForm");
        const output = document.getElementById("homeFaqJson");

        // Gallery management helpers
        const existingGallery = document.getElementById('existingGallery');
        const existingGalleryJson = document.getElementById('existingGalleryJson');
        const galleryUpload = document.getElementById('galleryUpload');
        const uploadPreview = document.getElementById('uploadPreview');

        // build initial kept list from DOM
        let keptGallery = [];
        if (existingGallery) {
            existingGallery.querySelectorAll('[data-path]').forEach(el => {
                keptGallery.push(el.getAttribute('data-path'));
            });
        }

        // remove existing image handler
        function bindRemoveExisting(btn) {
            btn.onclick = e => {
                const el = e.target.closest('[data-path]');
                if (!el) return;
                const path = el.getAttribute('data-path');
                keptGallery = keptGallery.filter(p => p !== path);
                el.remove();
            };
        }

        document.querySelectorAll('.removeExistingBtn').forEach(bindRemoveExisting);

        // preview selected uploads
        function previewFiles(files) {
            uploadPreview.innerHTML = '';
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                const wrap = document.createElement('div');
                wrap.className = 'relative border rounded overflow-hidden';
                reader.onload = ev => {
                    wrap.innerHTML = `<img src="${ev.target.result}" class="w-full h-32 object-cover">`;
                    uploadPreview.appendChild(wrap);
                };
                reader.readAsDataURL(file);
            });
        }

        if (galleryUpload) {
            galleryUpload.addEventListener('change', e => {
                previewFiles(e.target.files);
            });
        }

        // ensure existing gallery JSON is set before submit
        form.addEventListener('submit', () => {
            existingGalleryJson.value = JSON.stringify(keptGallery);
        });

        addBtn.onclick = () => {
            const row = document.createElement("div");
            row.className = "faq-row p-4 border rounded bg-white";
            row.innerHTML = `
            <input type="text" class="faq-q w-full p-2 border rounded mb-2" placeholder="Pertanyaan">
            <textarea class="faq-a w-full p-2 border rounded mb-2" rows="3" placeholder="Jawaban"></textarea>
            <button type="button" class="removeFaqBtn text-red-600 text-sm">Hapus</button>
        `;
            list.appendChild(row);
            row.querySelector(".removeFaqBtn").onclick = () => row.remove();
        };

        document.querySelectorAll(".removeFaqBtn").forEach(btn => {
            btn.onclick = e => e.target.closest(".faq-row").remove();
        });

        form.addEventListener("submit", () => {
            const items = [];
            document.querySelectorAll(".faq-row").forEach(r => {
                const q = r.querySelector(".faq-q").value.trim();
                const a = r.querySelector(".faq-a").value.trim();
                if (q !== "") items.push({
                    q,
                    a
                });
            });
            output.value = JSON.stringify(items);
        });
    });
</script>
@endsection