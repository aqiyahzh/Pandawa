@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Pengaturan Halaman KONTAK USER</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:underline">Kembali</a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">{{ session('error') }}</div>
    @endif
    {{-- ACTION HARUS SAMA DENGAN ROUTE UPDATE --}}
    <form action="{{ route('admin.settings.adminkontak.update') }}" method="POST" id="kontakForm">
        @csrf

        {{-- CONTACT ADDRESS --}}
        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Alamat Kontak</label>
            <textarea name="contact_address" rows="3" class="w-full p-3 border rounded">{{ $contactAddress ?? '' }}</textarea>
        </div>

        {{-- CONTACT EMAIL --}}
        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Email Kontak</label>
            <input type="email" name="contact_email" value="{{ $contactEmail ?? '' }}" class="w-full p-3 border rounded">
        </div>

        {{-- CONTACT PHONE --}}
        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Telepon Kontak</label>
            <input type="text" name="contact_phone" value="{{ $contactPhone ?? '' }}" class="w-full p-3 border rounded">
        </div>

        {{-- MAP IFRAME --}}
        <div>
            <label class="block text-sm font-semibold mb-2">Google Maps Embed (IFRAME)</label>
            <textarea name="contact_map_iframe" rows="4" class="w-full p-3 border rounded"
                placeholder="Paste kode embed Google Maps di sini">{{ old('contact_map_iframe', $mapIframe ?? '') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Gunakan iframe dari Google Maps → Share → Embed Map.</p>
        </div>
        <button type="submit" class="bg-yellow-500 text-white px-6 py-3 rounded hover:bg-yellow-600 transition">Simpan Perubahan</button>
    </form>
</div>
@endsection