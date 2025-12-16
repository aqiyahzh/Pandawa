<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = 'merchandise';

    protected $fillable = [
        'nama_barang',
        'foto',
        'harga',
        'deskripsi',
    ];

    // RELASI MULTIPLE IMAGES
    public function images()
    {
        return $this->hasMany(MerchImage::class, 'merch_id');
    }
}
