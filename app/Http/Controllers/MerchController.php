<?php

namespace App\Http\Controllers;
use App\Models\Merchant;
use App\Models\MerchImage;

use Illuminate\Http\Request;

class MerchController extends Controller
{
    public function index()
    {
        $items = Merchant::all();
        return view('admin.merch.index', compact('items'));
    }

    public function create()
    {
        return view('admin.merch.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'harga' => 'required|integer',
            'deskripsi' => 'required',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if (!file_exists(public_path('uploads/merch'))) {
            mkdir(public_path('uploads/merch'), 0777, true);
        }

        $merch = Merchant::create([
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {

                $filename = time() . '-' . $img->getClientOriginalName();
                $img->move(public_path('uploads/merch'), $filename);

                // simpan ke DB
                MerchImage::create([
                    'merch_id' => $merch->id,
                    'image' => $filename
                ]);

                // COPY ke project kedua
                copy(
                    public_path('uploads/merch/' . $filename),
                    'C:/laragon/www/pandawa/public/uploads/merch/' . $filename
                );
            }
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }



    public function edit($id)
    {
        $item = Merchant::findOrFail($id);
        return view('admin.merch.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $merch = Merchant::findOrFail($id);

        // update basic fields
        $merch->update([
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
        ]);

        // jika ada gambar baru
        if ($request->hasFile('images')) {
            // ==============================
            // HAPUS FOTO LAMA (dua project)
            // ==============================
            foreach ($merch->images as $old) {

                // hapus di project utama
                $path1 = public_path('uploads/merch/' . $old->image);
                if (file_exists($path1)) unlink($path1);

                // hapus di project kedua
                $path2 = 'C:/laragon/www/pandawa/public/uploads/merch/' . $old->image;
                if (file_exists($path2)) unlink($path2);

                // hapus record database
                $old->delete();
            }

            // ==============================
            // UPLOAD FOTO BARU + COPY
            // ==============================
            foreach ($request->file('images') as $img) {

                $filename = time() . '-' . $img->getClientOriginalName();
                $img->move(public_path('uploads/merch'), $filename);

                MerchImage::create([
                    'merch_id' => $merch->id,
                    'image' => $filename
                ]);

                // COPY ke project kedua
                copy(
                    public_path('uploads/merch/' . $filename),
                    'C:/laragon/www/pandawa/public/uploads/merch/' . $filename
                );
            }
        }

        return redirect()
            ->route('admin.merch.index')
            ->with('success', 'Merchandise berhasil diupdate!');
    }


    public function destroy($id)
    {
        $item = Merchant::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.merch.index')
            ->with('success', 'Merchandise berhasil dihapus!');
    }
}
