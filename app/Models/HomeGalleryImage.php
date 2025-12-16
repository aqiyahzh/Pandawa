<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeGalleryImage extends Model
{
    protected $table = 'home_gallery_images';

    protected $fillable = [
        'image'
    ];
}
