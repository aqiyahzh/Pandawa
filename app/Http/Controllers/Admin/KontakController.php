<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class KontakController extends Controller
{
    public function edit()
    {
        $keys = [
            'contact_address',
            'contact_email',
            'contact_phone',
            'contact_map_iframe',
        ];

        $settings = Setting::whereIn('key', $keys)->get()->keyBy('key');

        $contactAddress = $settings['contact_address']->value ?? '';
        $contactEmail   = $settings['contact_email']->value ?? '';
        $contactPhone   = $settings['contact_phone']->value ?? '';
        $mapIframe      = $settings['contact_map_iframe']->value ?? '';


        return view('admin.settings.adminkontak', compact(
            'contactAddress',
            'contactEmail',
            'contactPhone',
            'mapIframe'
        ));
        
    }

    public function update(Request $request)
    {
        $request->validate([
            'contact_address' => 'nullable|string|max:1000',
            'contact_email'   => 'nullable|email|max:255',
            'contact_phone'   => 'nullable|string|max:20',
            'contact_map_iframe' => 'nullable|string',
        ]);

        // helper penyimpan agar SAMA dengan SettingController versi kamu
        $updatesetting = function ($key, $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            Cache::forget("setting_{$key}");
        };

        $updatesetting('contact_address', $request->contact_address);
        $updatesetting('contact_email',   $request->contact_email);
        $updatesetting('contact_phone',   $request->contact_phone);
        $updatesetting('contact_map_iframe', $request->contact_map_iframe);

        

        return back()->with('success', 'Halaman Tentang berhasil diperbarui.');
    }
}
