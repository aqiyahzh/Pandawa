<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;

class MerchandiseController extends Controller
{
    public function show($id)
    {
        $item = Merchandise::findOrFail($id);
        return view('merch-detail', compact('item'));
    }
}
