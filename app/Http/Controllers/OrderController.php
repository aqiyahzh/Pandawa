<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Setting;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'phone' => 'required',
            'jumlah' => 'required|integer',
            'catatan' => 'nullable',
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'id_barang' => 'required'
        ]);

        // Upload bukti transfer
        $filename = time() . '-' . $request->file('bukti')->getClientOriginalName();
        $request->file('bukti')->move(public_path('uploads'), $filename);

        // COPY ke project ADMIN
        copy(
            public_path('uploads/' . $filename),
            'C:/laragon/www/pandadmin/public/uploads/' . $filename
        );

        // Simpan ke database
        $order = Order::create([
            'nama_pemesan' => $request->nama,
            'no_hp' => $request->phone,
            'jumlah' => $request->jumlah,
            'catatan' => $request->catatan,
            'bukti_transfer' => $filename,
            'id_barang' => $request->id_barang,
        ]);

        // Redirect ke WhatsApp (ambil dari setting jika ada)
        $waNumber = Setting::where('key', 'order_whatsapp')->value('value') ?? '6281770059576';
        $message = rawurlencode("
Halo, saya ingin memesan merchandise.

🛒 Produk: {$order->barang->nama_barang}
📦 Jumlah: {$order->jumlah}
👤 Nama: {$order->nama_pemesan}
📞 No HP: {$order->no_hp}
📝 Catatan: {$order->catatan}

Mohon diproses ya admin 🙏
        ");

        return redirect("https://wa.me/$waNumber?text=$message");
    }
}
