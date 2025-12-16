@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Daftar Order</h1>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Produk</th>
                    <th class="px-4 py-2 text-left">Pemesan</th>
                    <th class="px-4 py-2 text-left">Jumlah</th>
                    <th class="px-4 py-2 text-left">Total</th>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Bukti</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $o)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $o->id }}</td>
                        <td class="px-4 py-3">{{ $o->barang->nama_barang ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $o->nama_pemesan }} ({{ $o->no_hp }})</td>
                        <td class="px-4 py-3">{{ $o->jumlah }}</td>
                        <td class="px-4 py-3">Rp {{ number_format(($o->jumlah * ($o->barang->harga ?? 0)),0,',','.') }}</td>
                        <td class="px-4 py-3">{{ $o->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">
                            @if($o->bukti_transfer)
                                <a href="{{ asset('uploads/'.$o->bukti_transfer) }}" target="_blank" class="text-sm text-indigo-600">Lihat</a>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.orders.edit', $o->id) }}" class="text-sm text-yellow-600 mr-3"><i class="bi bi-pencil-square"></i>Edit</a>
                            <form action="{{ route('admin.orders.destroy', $o->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus order #{{ $o->id }}?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-sm text-red-600"><i class="bi bi-trash3"></i>Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
