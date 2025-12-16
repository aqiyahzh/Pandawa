<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $fillable = ['key', 'value'];

    // helper untuk menyimpan array/obj sebagai JSON
    public function getValueAttribute($val)
    {
        $decoded = json_decode($val, true);
        return $decoded === null ? $val : $decoded;
    }

    public function setValueAttribute($val)
    {
        // jika array/object => simpan as JSON
        if (is_array($val) || is_object($val)) {
            $this->attributes['value'] = json_encode($val);
        } else {
            $this->attributes['value'] = $val;
        }
    }
}
