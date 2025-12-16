<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchImage extends Model
{
    protected $fillable = ['merch_id', 'image'];

    public function merch()
    {
        return $this->belongsTo(Merchandise::class, 'merch_id');
    }
}
