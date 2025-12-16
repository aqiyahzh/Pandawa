<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Merchandise extends Model
{
    use HasFactory;

    protected $table = 'merchandise';

    protected $fillable = [
        'nama_barang',
        'deskripsi',
        'harga',
        'foto'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'id_barang');
    }
    public function images()
    {
        return $this->hasMany(MerchandiseImage::class, 'merch_id');
    }
}
