@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto mt-10">

    {{-- TITLE --}}
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Merchandise</h1>

    {{-- FORM CARD --}}
    <div class="bg-white shadow-lg rounded-xl p-8 border border-gray-200">

        <form action="{{ route('admin.merch.update', $item->id) }}"
            method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            {{-- NAMA BARANG --}}
            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-2">Nama Barang</label>
                <input type="text"
                    name="nama_barang"
                    value="{{ $item->nama_barang }}"
                    autocomplete="off"
                    class="w-full bg-white text-gray-800 border border-gray-400 
                    rounded-lg px-3 py-2 
                    focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-600"
                    required>
            </div>

            {{-- HARGA --}}
            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-2">Harga</label>
                <input type="number"
                    name="harga"
                    value="{{ $item->harga }}"
                    autocomplete="off"
                    class="w-full bg-white text-gray-800 border border-gray-400 
                    rounded-lg px-3 py-2 
                    focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-600"
                    required>
            </div>

            {{-- FOTO LAMA --}}
            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-3">Foto Lama</label>

                <div class="flex flex-wrap gap-4">
                    @foreach ($item->images as $img)
                    <img src="{{ asset('uploads/merch/'.$img->image) }}"
                        class="w-28 h-28 object-cover rounded-lg shadow border">
                    @endforeach
                </div>
            </div>

            {{-- FOTO BARU --}}
            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-2">
                    Upload Foto Baru (bisa banyak)
                </label>

                <input
                    type="file"
                    name="images[]"
                    id="imageInput"
                    multiple
                    autocomplete="off"
                    class="w-full bg-white text-gray-800 border border-gray-400 
                    rounded-lg px-3 py-2 
                    focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-600"
                    required>

                <div id="preview" class="mt-4 flex flex-wrap gap-4"></div>
            </div>

            {{-- SCRIPT PREVIEW FOTO BARU --}}
            <script>
                document.getElementById('imageInput').addEventListener('change', function() {
                    let preview = document.getElementById('preview');
                    preview.innerHTML = "";

                    for (let i = 0; i < this.files.length; i++) {
                        let img = document.createElement('img');
                        img.src = URL.createObjectURL(this.files[i]);
                        img.classList.add(
                            "w-28", "h-28", "object-cover",
                            "rounded-lg", "shadow", "border"
                        );
                        preview.appendChild(img);
                    }
                });
            </script>

            {{-- DESKRIPSI --}}
            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi"
                    rows="4"
                    autocomplete="off"
                    class="w-full bg-white text-gray-800 border border-gray-400 
                    rounded-lg px-3 py-2 
                    focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-600"
                    required>{{ $item->deskripsi }}</textarea>
            </div>

            {{-- SUCCESS MSG --}}
            @if (session('success'))
            <div class="p-4 mb-6 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            {{-- BUTTONS --}}
            <div class="flex items-center gap-4">
                <button class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-lg font-semibold shadow">
                    Update
                </button>

                <a href="{{ route('admin.merch.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 px-6 py-2 rounded-lg font-semibold shadow text-gray-800">
                    Kembali
                </a>
            </div>

        </form>

    </div>

</div>
@endsection