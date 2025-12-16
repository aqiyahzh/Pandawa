@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto p-8">

    <h2 class="text-2xl font-bold text-gray-700 mb-6">Tambah Merchandise</h2>

    <div class="bg-white shadow rounded-lg p-8 border border-gray-200">

        <form action="{{ route('admin.merch.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6">

            @csrf

            <!-- NAMA -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Nama Barang</label>
                <input type="text" name="nama_barang"
                    autocomplete="off"
                    class="w-full bg-white text-gray-800 border border-gray-400 
           rounded-lg px-3 py-2 
           focus:ring-2 focus:ring-yellow-500/50 focus:border-yellow-600"
                    required>
            </div>

            <!-- HARGA -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Harga</label>
                <input type="number" name="harga"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-yellow-500 focus:border-yellow-500"
                    required>
            </div>

            <!-- FOTO -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Foto (Bisa Banyak)</label>
                <input type="file" name="images[]" id="imageInput"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-yellow-500 focus:border-yellow-500"
                    multiple>

                <p class="text-sm text-gray-500 mt-1">Pilih lebih dari satu foto.</p>

                <div id="preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
            </div>


            <script>
                document.getElementById('imageInput').addEventListener('change', function() {
                    let preview = document.getElementById('preview');
                    preview.innerHTML = "";

                    [...this.files].forEach(file => {
                        let img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.className = "w-full h-28 object-cover rounded-lg border";
                        preview.appendChild(img);
                    });
                });
            </script>

            <!-- DESKRIPSI -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-yellow-500 focus:border-yellow-500"></textarea>
            </div>

            <!-- BUTTON -->
            <div class="flex items-center gap-4 pt-4">
                <button class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg shadow">
                    Simpan
                </button>

                <a href="{{ route('admin.merch.index') }}"
                    class="px-6 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-700">
                    Kembali
                </a>
            </div>

        </form>

    </div>
</div>

@endsection