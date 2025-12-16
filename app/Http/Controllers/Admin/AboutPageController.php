<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class AboutPageController extends Controller
{
    public function edit()
    {
        $keys = [
            'about_Title',
            'about_Subtitle',
            'about_Event1',
            'about_Event2',
            'about_Event3',
            'about_EventSub1',
            'about_EventSub2',
            'about_EventSub3',
            'about_Text1',
            'about_Text2',
            'about_Text3',
            'about_Text4',
            'panelImage1',
            'panelImage2',
            'about_timeline',
        ];

        $settings = Setting::whereIn('key', $keys)->get()->keyBy('key');

        $aboutTitle    = $settings['about_Title']->value ?? '';
        $aboutSubtitle = $settings['about_Subtitle']->value ?? '';
        $aboutEvent1    = $settings['about_Event1']->value ?? '';
        $aboutEvent2    = $settings['about_Event2']->value ?? '';
        $aboutEvent3    = $settings['about_Event3']->value ?? '';
        $aboutEventSub1 = $settings['about_EventSub1']->value ?? '';
        $aboutEventSub2 = $settings['about_EventSub2']->value ?? '';
        $aboutEventSub3 = $settings['about_EventSub3']->value ?? '';
        $aboutText1     = $settings['about_Text1']->value ?? '';
        $aboutText2     = $settings['about_Text2']->value ?? '';
        $aboutText3     = $settings['about_Text3']->value ?? '';
        $aboutText4     = $settings['about_Text4']->value ?? '';
        $panelImage1   = $settings['panelImage1']->value ?? '';
        $panelImage2   = $settings['panelImage2']->value ?? '';
        $timelineRaw   = $settings['about_timeline']->value ?? '[]';


        return view('admin.settings.aboutpage', compact(
            'aboutTitle',
            'aboutSubtitle',
            'aboutEvent1',
            'aboutEvent2',
            'aboutEvent3',
            'aboutEventSub1',
            'aboutEventSub2',
            'aboutEventSub3',
            'aboutText1',
            'aboutText2',
            'aboutText3',
            'aboutText4',
            'panelImage1',
            'panelImage2',
            'timelineRaw'
        ));
        
    }

    public function update(Request $request)
    {
        $request->validate([
            'about_Title'    => 'nullable|string|max:255',
            'about_Subtitle' => 'nullable|string|max:500',
            'about_Event1'    => 'nullable|string|max:3000',
            'about_Event2'    => 'nullable|string|max:3000',
            'about_Event3'    => 'nullable|string|max:3000',
            'about_EventSub1' => 'nullable|string|max:3000',
            'about_EventSub2' => 'nullable|string|max:3000',
            'about_EventSub3' => 'nullable|string|max:3000',
            'about_Text1'     => 'nullable|string|max:5000',
            'about_Text2'     => 'nullable|string|max:5000',
            'about_Text3'     => 'nullable|string|max:5000',
            'about_Text4'     => 'nullable|string|max:5000',
            'panelImage1'   => 'nullable|image|max:2048',
            'panelImage2'   => 'nullable|image|max:2048',
            'remove_panel1' => 'nullable',
            'remove_panel2' => 'nullable',
            'timeline_json' => 'nullable|string',
        ]);

        // helper penyimpan agar SAMA dengan SettingController versi kamu
        $updatesetting = function ($key, $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            Cache::forget("setting_{$key}");
        };

        $updatesetting('about_Title',    $request->about_Title);
        $updatesetting('about_Subtitle', $request->about_Subtitle);
        $updatesetting('about_Event1',    $request->about_Event1);
        $updatesetting('about_Event2',    $request->about_Event2);
        $updatesetting('about_Event3',    $request->about_Event3);
        $updatesetting('about_EventSub1', $request->about_EventSub1);
        $updatesetting('about_EventSub2', $request->about_EventSub2);
        $updatesetting('about_EventSub3', $request->about_EventSub3);
        $updatesetting('about_Text1',     $request->about_Text1);
        $updatesetting('about_Text2',     $request->about_Text2);
        $updatesetting('about_Text3',     $request->about_Text3);
        $updatesetting('about_Text4',     $request->about_Text4);
        $updatesetting('about_timeline',  $request->timeline_json);

        // Handle panelImage removals and uploads (allow delete + replace)
        // panelImage1: remove if requested
        if ($request->has('remove_panel1')) {
            $existing = Setting::where('key', 'panelImage1')->value('value') ?? '';
            if ($existing) {
                $full = public_path($existing);
                if (file_exists($full)) @unlink($full);
            }
            $updatesetting('panelImage1', '');
        }

        // panelImage2: remove if requested
        if ($request->has('remove_panel2')) {
            $existing = Setting::where('key', 'panelImage2')->value('value') ?? '';
            if ($existing) {
                $full = public_path($existing);
                if (file_exists($full)) @unlink($full);
            }
            $updatesetting('panelImage2', '');
        }

        // Handle panelImage1 upload (replace existing file if present)
        if ($request->hasFile('panelImage1')) {
            $file = $request->file('panelImage1');
            $existing = Setting::where('key', 'panelImage1')->value('value') ?? '';
            if ($existing) {
                $full = public_path($existing);
                if (file_exists($full)) @unlink($full);
            }
            if (!file_exists(public_path('aboutPanel'))) {
                mkdir(public_path('aboutPanel'), 0755, true);
            }
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\._-]/', '_', $file->getClientOriginalName());
            $file->move(public_path('aboutPanel'), $filename);
            $updatesetting('panelImage1', 'aboutPanel/' . $filename);
        }

        // Handle panelImage2 upload (replace existing file if present)
        if ($request->hasFile('panelImage2')) {
            $file = $request->file('panelImage2');
            $existing = Setting::where('key', 'panelImage2')->value('value') ?? '';
            if ($existing) {
                $full = public_path($existing);
                if (file_exists($full)) @unlink($full);
            }
            if (!file_exists(public_path('aboutPanel'))) {
                mkdir(public_path('aboutPanel'), 0755, true);
            }
            $filename = time() . '_2_' . preg_replace('/[^A-Za-z0-9\._-]/', '_', $file->getClientOriginalName());
            $file->move(public_path('aboutPanel'), $filename);
            $updatesetting('panelImage2', 'aboutPanel/' . $filename);
        }

        // TIMELINE JSON
        $time = json_decode($request->timeline_json ?? '[]', true);
        if (!is_array($time)) $time = [];
        $updatesetting('about_timeline', json_encode($time));

        return back()->with('success', 'Halaman Tentang berhasil diperbarui.');
    }
}
