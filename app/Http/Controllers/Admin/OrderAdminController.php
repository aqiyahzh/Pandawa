<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;

class OrderAdminController extends Controller
{
    public function index()
    {
        $orders = Order::with('barang')->orderByDesc('created_at')->paginate(25);
        return view('admin.orders.index', compact('orders'));
    }

    public function edit($id)
    {
        $order = Order::with('barang')->findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:60',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $order->nama_pemesan = $request->input('nama_pemesan');
        $order->no_hp = $request->input('no_hp');
        $order->jumlah = $request->input('jumlah');
        $order->catatan = $request->input('catatan');

        // handle replace bukti
        if ($request->hasFile('bukti')) {
            // delete old file if exists
            if ($order->bukti_transfer) {
                $old = public_path('uploads/' . $order->bukti_transfer);
                if (file_exists($old)) @unlink($old);
                // try delete copy in pandadmin if exists
                $adminCopy = 'C:/laragon/www/pandadmin/public/uploads/' . $order->bukti_transfer;
                if (file_exists($adminCopy)) @unlink($adminCopy);
            }

            $f = $request->file('bukti');
            $filename = time() . '_' . Str::random(6) . '.' . $f->getClientOriginalExtension();
            $f->move(public_path('uploads'), $filename);
            // copy to admin project if available
            @copy(public_path('uploads/' . $filename), 'C:/laragon/www/pandadmin/public/uploads/' . $filename);
            $order->bukti_transfer = $filename;
        }

        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Order updated.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // delete bukti file
        if ($order->bukti_transfer) {
            $full = public_path('uploads/' . $order->bukti_transfer);
            if (file_exists($full)) @unlink($full);
            $adminCopy = 'C:/laragon/www/pandadmin/public/uploads/' . $order->bukti_transfer;
            if (file_exists($adminCopy)) @unlink($adminCopy);
        }

        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
    }
}