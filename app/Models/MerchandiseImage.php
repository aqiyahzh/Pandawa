<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchandiseImage extends Model
{
    protected $table = 'merch_images'; // tabel dari db

    protected $fillable = [
        'merch_id',
        'image'
    ];

    public $timestamps = false;
}
