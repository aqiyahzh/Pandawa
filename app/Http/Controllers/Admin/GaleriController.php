<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GaleriController extends Controller
{
    public function edit()
    {
        $raw = Setting::where('key', 'pandagaleri')->value('value') ?? '[]';
        $items = [];
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) $items = $decoded;
        } elseif (is_array($raw)) {
            $items = $raw;
        }

        return view('admin.settings.admingaleri', compact('items'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'gallery_images.*' => 'nullable|image|max:6144',
            'existing_items_json' => 'nullable|string',
            'video_iframes' => 'nullable|array',
            'video_iframes.*' => 'nullable|string',
        ]);

        $settingKey = 'pandagaleri';

        $raw = Setting::where('key', $settingKey)->value('value') ?? '[]';
        $current = [];
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) $current = $decoded;
        } elseif (is_array($raw)) {
            $current = $raw;
        }

        // kept items from admin (JSON array of items)
        $keptJson = $request->input('existing_items_json', '[]');
        $kept = [];
        if (is_string($keptJson)) {
            $decoded = json_decode($keptJson, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) $kept = $decoded;
        } elseif (is_array($keptJson)) {
            $kept = $keptJson;
        }

        // normalize kept items: accept only image(path) or video(iframe)
        $keptNormalized = [];
        foreach ($kept as $it) {
            if (!is_array($it)) continue;
            $type = $it['type'] ?? null;
            if ($type === 'image' && !empty($it['path'])) {
                $keptNormalized[] = ['type' => 'image', 'path' => $it['path']];
            } elseif ($type === 'video' && !empty($it['iframe'])) {
                $keptNormalized[] = ['type' => 'video', 'iframe' => $it['iframe']];
            }
        }

        // delete image files that were removed (compare by path)
        $currentImagePaths = [];
        foreach ($current as $it) {
            if (is_array($it) && ($it['type'] ?? '') === 'image' && !empty($it['path'])) {
                $currentImagePaths[] = $it['path'];
            }
        }
        $keptImagePaths = [];
        foreach ($keptNormalized as $it) {
            if ($it['type'] === 'image') $keptImagePaths[] = $it['path'];
        }
        $toRemove = array_values(array_diff($currentImagePaths, $keptImagePaths));
        foreach ($toRemove as $rm) {
            $full = public_path($rm);
            if (file_exists($full)) {
                @unlink($full);
            }
        }

        // start final list with kept normalized items
        $final = $keptNormalized;

        // handle new uploads
        if ($request->hasFile('gallery_images')) {
            $files = $request->file('gallery_images');
            $uploadDir = public_path('pandagaleri');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            foreach ($files as $f) {
                if ($f && $f->isValid()) {
                    $filename = time() . '_' . Str::random(8) . '.' . $f->getClientOriginalExtension();
                    $f->move($uploadDir, $filename);
                    $final[] = ['type' => 'image', 'path' => 'pandagaleri/' . $filename];
                }
            }
        }

        // handle video iframes
        $videos = $request->input('video_iframes', []);
        if (is_array($videos)) {
            foreach ($videos as $v) {
                $v = trim($v ?? '');
                if ($v !== '') $final[] = ['type' => 'video', 'iframe' => $v];
            }
        }

        Setting::updateOrCreate(['key' => $settingKey], ['value' => json_encode(array_values($final))]);
        Cache::forget("setting_{$settingKey}");

        return back()->with('success', 'Galeri berhasil diperbarui.');
    }
}
