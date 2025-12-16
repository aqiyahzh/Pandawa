@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Edit Order #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500">Kembali</a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama Pemesan</label>
            <input type="text" name="nama_pemesan" class="w-full p-3 border rounded" value="{{ old('nama_pemesan', $order->nama_pemesan) }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nomor HP</label>
            <input type="text" name="no_hp" class="w-full p-3 border rounded" value="{{ old('no_hp', $order->no_hp) }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Jumlah</label>
            <input type="number" name="jumlah" class="w-full p-3 border rounded" value="{{ old('jumlah', $order->jumlah) }}" min="1" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Catatan</label>
            <textarea name="catatan" rows="4" class="w-full p-3 border rounded">{{ old('catatan', $order->catatan) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Bukti Transfer (ganti jika perlu)</label>
            @if($order->bukti_transfer)
                <div class="mb-2">
                    <a href="{{ asset('uploads/' . $order->bukti_transfer) }}" target="_blank" class="text-indigo-600">Lihat bukti saat ini</a>
                </div>
            @endif
            <input type="file" name="bukti" accept="image/*" class="w-full p-3 border rounded">
        </div>

        <div class="flex gap-3">
            <button class="bg-yellow-600 text-white px-4 py-2 rounded">Simpan</button>
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Hapus order ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Hapus</button>
            </form>
        </div>
    </form>
</div>
@endsection
