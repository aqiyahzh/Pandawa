@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Daftar Merchandise</h2>
            <p class="text-gray-500 mt-1">Kelola koleksi merchandise Pandawa</p>
        </div>

        <div class="flex items-center gap-3">
            <form action="{{ route('admin.merch.index') }}" method="GET" class="flex items-center gap-2">
                <label for="q" class="sr-only">Cari</label>
                <input id="q" name="q" value="{{ request('q') }}" placeholder="Cari nama atau deskripsi..." 
                       class="border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300 w-56"
                       type="search">
                <button type="submit" class="px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg">Cari</button>
                @if(request('q'))
                    <a href="{{ route('admin.merch.index') }}" class="text-sm text-gray-500 ml-2 hover:underline">Reset</a>
                @endif
            </form>

            <a href="{{ route('admin.merch.create') }}" class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded-lg shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah</span>
            </a>

            <a href="{{ route('admin.merch.index', array_merge(request()->all(), ['export'=>'csv'])) }}" class="inline-flex items-center gap-2 border border-gray-200 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Export
            </a>

            {{-- View toggle --}}
            <div class="ml-2 inline-flex items-center bg-white border rounded-lg overflow-hidden">
                <button id="tableViewBtn" class="px-3 py-2 text-sm text-gray-600 bg-white hover:bg-gray-50" data-view="table" aria-pressed="true">Table</button>
                <button id="cardViewBtn" class="px-3 py-2 text-sm text-gray-600 hover:bg-gray-50" data-view="card">Cards</button>
            </div>
        </div>
    </div>

    {{-- Success flash --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Content area --}}
    <div id="tableView" class="space-y-4">
        {{-- Table card --}}
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] table-auto">
                    <thead class="bg-gradient-to-r from-yellow-50 to-yellow-100 text-left sticky top-0">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-700">Foto</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-700">Nama Barang</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-700">Harga</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($items as $item)
                            <tr class="border-b hover:bg-gray-50 transition">
                                {{-- Foto --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="flex items-center">
                                        @if($item->images && $item->images->count())
                                            <img src="{{ asset('uploads/merch/' . $item->images->first()->image) }}"
                                                 alt="{{ $item->nama_barang }}"
                                                 class="w-16 h-16 rounded-md object-cover shadow-sm">
                                        @else
                                            <div class="w-16 h-16 rounded-md bg-gray-100 flex items-center justify-center text-gray-400">No Image</div>
                                        @endif
                                        @if($item->images && $item->images->count() > 1)
                                            <div class="ml-2 text-xs text-gray-500">+{{ $item->images->count() - 1 }}</div>
                                        @endif
                                    </div>
                                </td>

                                {{-- Nama --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="font-semibold text-gray-800">{{ $item->nama_barang }}</div>
                                    <div class="text-xs text-gray-500 mt-1">ID: {{ $item->id }}</div>
                                </td>

                                {{-- Harga --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="inline-block bg-yellow-50 text-yellow-700 px-3 py-1 rounded-full font-semibold">Rp {{ number_format($item->harga,0,',','.') }}</div>
                                </td>

                                {{-- Status (contoh: stok atau fitur custom) --}}
                                <td class="px-6 py-4 align-top">
                                    {{-- Jika ingin menampilkan stok atau label lainnya, sesuaikan --}}
                                    <span class="inline-block text-xs px-2 py-1 rounded-full bg-green-50 text-green-700">Active</span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 align-top text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.merch.edit', $item->id) }}"
                                            class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.merch.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus merchandise ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm">
                                                <i class="bi bi-trash3"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <p class="text-lg font-medium">📭 Tidak ada merchandise</p>
                                    <p class="text-sm mt-2">Mulai dengan <a href="{{ route('admin.merch.create') }}" class="text-yellow-600 hover:underline">menambah merchandise baru</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination (if available) --}}
            @if(method_exists($items, 'links'))
                <div class="px-6 py-4 border-t bg-gray-50 flex items-center justify-between">
                    <div class="text-sm text-gray-600">Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} dari {{ $items->total() ?? $items->count() }}</div>
                    <div>
                        {{ $items->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Card view (hidden by default) --}}
    <div id="cardView" class="hidden">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($items as $item)
                <div class="bg-white rounded-2xl shadow p-4 flex flex-col">
                    <div class="relative">
                        @if($item->images && $item->images->count())
                            <img src="{{ asset('uploads/merch/' . $item->images->first()->image) }}" alt="{{ $item->nama_barang }}" class="w-full h-44 object-cover rounded-lg">
                        @else
                            <div class="w-full h-44 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">No Image</div>
                        @endif
                        <div class="absolute top-3 right-3">
                            <div class="bg-white/80 px-2 py-1 rounded-md text-xs font-semibold text-gray-800">Rp {{ number_format($item->harga,0,',','.') }}</div>
                        </div>
                    </div>

                    <div class="mt-3 flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 truncate">{{ $item->nama_barang }}</h3>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-3" title="{{ $item->deskripsi }}">{{ $item->deskripsi }}</p>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-sm text-gray-500">ID: {{ $item->id }}</div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.merch.edit', $item->id) }}" class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm">Edit</a>
                            <form action="{{ route('admin.merch.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus merchandise ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded-lg text-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-500 py-12">
                    <p class="text-lg font-medium">Tidak ada merchandise</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination for card view --}}
        @if(method_exists($items, 'links'))
            <div class="mt-6">
                {{ $items->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Simple JS to toggle between table/card views --}}
<script>
    (function(){
        const tableBtn = document.getElementById('tableViewBtn');
        const cardBtn = document.getElementById('cardViewBtn');
        const tableView = document.getElementById('tableView');
        const cardView = document.getElementById('cardView');

        function setView(view) {
            if (view === 'card') {
                tableView.classList.add('hidden');
                cardView.classList.remove('hidden');
                tableBtn.setAttribute('aria-pressed', 'false');
                cardBtn.setAttribute('aria-pressed', 'true');
                tableBtn.classList.remove('bg-gray-100');
                cardBtn.classList.add('bg-gray-100');
            } else {
                cardView.classList.add('hidden');
                tableView.classList.remove('hidden');
                cardBtn.setAttribute('aria-pressed', 'false');
                tableBtn.setAttribute('aria-pressed', 'true');
                cardBtn.classList.remove('bg-gray-100');
                tableBtn.classList.add('bg-gray-100');
            }
        }

        // default to table
        setView('table');

        tableBtn.addEventListener('click', () => setView('table'));
        cardBtn.addEventListener('click', () => setView('card'));
    })();
</script>

<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection