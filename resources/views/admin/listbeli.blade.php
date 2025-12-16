@extends('layouts.admin')

@section('content')
<div class="container">

    <h2 class="mb-4">Daftar Pesanan</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama Pemesan</th>
                <th>No HP</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Bukti Transfer</th>
                <th>Catatan</th>
                <th>Tanggal</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $o)
            <tr>
                <td>{{ $o->nama_pemesan }}</td>
                <td>{{ $o->no_hp }}</td>
                <td>{{ $o->barang->nama_barang }}</td>
                <td>{{ $o->jumlah }}</td>
                <td>
                    <img src="{{ asset('uploads/' . $o->bukti_transfer) }}"
                        width="80">
                </td>
                <td>{{ $o->catatan }}</td>
                <td>{{ $o->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $orders->links() }}
    </div>

</div>
@endsection