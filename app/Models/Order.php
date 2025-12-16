<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'nama_pemesan',
        'no_hp',
        'jumlah',
        'catatan',
        'bukti_transfer',
        'id_barang'
    ];

    public function barang()
    {
        return $this->belongsTo(Merchandise::class, 'id_barang');
    }
}
