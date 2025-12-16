<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function edit()
    {
        // Ambil hanya key yang diperlukan
        $keys = [
            'about_vision',
            'about_mission',
            'about_values',
            'home_faq',
            'home_hero_title',
            'home_hero_subtitle',
            'home_hero_subtext',
            'home_hero_tagline',
            'home_hero_image',
            'home_gallery_images',
            // order-related settings
            'order_whatsapp',
            'order_payment_instructions',
        ];

        $settings = Setting::whereIn('key', $keys)->get()->keyBy('key');

        $aboutVision  = $settings['about_vision']->value ?? '';
        $aboutMission = $settings['about_mission']->value ?? '';
        $aboutValues  = $settings['about_values']->value ?? '';
        $heroTitle = $settings['home_hero_title']->value ?? '';
        $heroSubtitle = $settings['home_hero_subtitle']->value ?? '';
        $heroSubtext = $settings['home_hero_subtext']->value ?? '';
        $heroTagline = $settings['home_hero_tagline']->value ?? '';
        $heroImageRaw = $settings['home_hero_image']->value ?? '[]';
        $galleryImageRaw = $settings['home_gallery_images']->value ?? '[]';

        $orderWhatsapp = $settings['order_whatsapp']->value ?? '';
        $orderPaymentInstructions = $settings['order_payment_instructions']->value ?? '';

        // Handle Hero Images robustly
        $heroImages = [];
        if (is_string($heroImageRaw)) {
            $decoded = json_decode($heroImageRaw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $heroImages = $decoded;
            }
        } elseif (is_array($heroImageRaw)) {
            $heroImages = $heroImageRaw;
        }

        // Handle Gallery Images robustly (if needed in future)
        $galleryImages = [];
        if (is_string($galleryImageRaw)) {
            $decoded = json_decode($galleryImageRaw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $galleryImages = $decoded;
            }
        } elseif (is_array($galleryImageRaw)) {
            $galleryImages = $galleryImageRaw;
        }
        

        // Handle FAQ robus
        $homeFaqRaw = $settings['home_faq']->value ?? '[]';
        $faqItems = [];

        if (is_string($homeFaqRaw)) {
            $decoded = json_decode($homeFaqRaw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $faqItems = $decoded;
            }
        } elseif (is_array($homeFaqRaw)) {
            $faqItems = $homeFaqRaw;
        }

        return view('admin.settings.edit', compact(
            'aboutVision',
            'aboutMission',
            'aboutValues',
            'faqItems',
            'heroTitle',
            'heroSubtitle',
            'heroSubtext',
            'heroTagline',
            'heroImages',
            'galleryImages',
            'orderWhatsapp',
            'orderPaymentInstructions',
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'about_vision'  => 'nullable|string|max:3000',
            'about_mission' => 'nullable|string|max:3000',
            'about_values'  => 'nullable|string|max:3000',
            'home_faq_json' => 'nullable|string',
            'home_hero_title' => 'nullable|string|max:255',
            'home_hero_subtitle' => 'nullable|string|max:500',
            'home_hero_subtext' => 'nullable|string|max:1000',
            'home_hero_tagline' => 'nullable|string|max:255',
            'home_hero_images.*' => 'nullable|image|max:2048',
            'remove_hero' => 'nullable|array',
            'remove_hero.*' => 'string',
            'order_whatsapp' => 'nullable|string|max:60',
            'order_payment_instructions' => 'nullable|string',
        ]);

        // helper penyimpan
        $updateSetting = function ($key, $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            Cache::forget("setting_{$key}");
        };

        // simpan visi/misi/nilai
        $updateSetting('about_vision',  $request->about_vision);
        $updateSetting('about_mission', $request->about_mission);
        $updateSetting('about_values',  $request->about_values);
        $updateSetting('home_hero_title', $request->home_hero_title);
        $updateSetting('home_hero_subtitle', $request->home_hero_subtitle);
        $updateSetting('home_hero_subtext', $request->home_hero_subtext);
        $updateSetting('home_hero_tagline', $request->home_hero_tagline);
        
        // handle hero images as array stored in setting (JSON)
        $maxImages = 7;
        $settingRaw = Setting::where('key', 'home_hero_image')->value('value') ?? '[]';
        $current = [];
        if (is_string($settingRaw)) {
            $decoded = json_decode($settingRaw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) $current = $decoded;
        } elseif (is_array($settingRaw)) {
            $current = $settingRaw;
        }

        // remove selected images (files stored under public/uploads/heroimage/...)
        $toRemove = $request->input('remove_hero', []);
        if (is_array($toRemove) && count($toRemove)) {
            foreach ($toRemove as $rm) {
                $key = array_search($rm, $current);
                if ($key !== false) {
                    $full = public_path($rm);
                    if (file_exists($full)) {
                        @unlink($full);
                    }
                    unset($current[$key]);
                }
            }
            $current = array_values($current);
        }

        // add new uploads — move files into public/uploads/heroimage
        if ($request->hasFile('home_hero_images')) {
            $files = $request->file('home_hero_images');
            $uploadDir = public_path('uploads/heroimage');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            foreach ($files as $f) {
                if (count($current) >= $maxImages) break;
                if ($f && $f->isValid()) {
                    $filename = time() . '_' . Str::random(8) . '.' . $f->getClientOriginalExtension();
                    $f->move($uploadDir, $filename);
                    $current[] = 'uploads/heroimage/' . $filename;
                }
            }
        }
 
        // save back as JSON
        $updateSetting('home_hero_image', json_encode(array_values($current)));

        // save order-related settings (WhatsApp number for redirect and payment instructions HTML/text)
        $rawWa = trim($request->input('order_whatsapp', ''));
        // normalize WA number: strip non-digits, convert leading 0 -> remove, ensure country code 62
        $digits = preg_replace('/\D+/', '', $rawWa);
        if ($digits !== '') {
            // remove leading zeros
            $digits = preg_replace('/^0+/', '', $digits);
            if (preg_match('/^62/', $digits)) {
                $normalized = $digits;
            } elseif (preg_match('/^8/', $digits)) {
                // local '8xxx' -> prepend '62'
                $normalized = '62' . $digits;
            } else {
                // fallback, keep digits as-is
                $normalized = $digits;
            }
        } else {
            $normalized = '';
        }

        $updateSetting('order_whatsapp', $normalized);
        $updateSetting('order_payment_instructions', $request->input('order_payment_instructions'));

        // -------------------------
        // Gallery images handling
        // -------------------------
        $gallerySettingRaw = Setting::where('key', 'home_gallery_images')->value('value') ?? '[]';
        $galleryCurrent = [];
        if (is_string($gallerySettingRaw)) {
            $decoded = json_decode($gallerySettingRaw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) $galleryCurrent = $decoded;
        } elseif (is_array($gallerySettingRaw)) {
            $galleryCurrent = $gallerySettingRaw;
        }

        // existing images kept by admin (from hidden input existing_gallery_json)
        $keptJson = $request->input('existing_gallery_json', '[]');
        $kept = [];
        if (is_string($keptJson)) {
            $decoded = json_decode($keptJson, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) $kept = $decoded;
        } elseif (is_array($keptJson)) {
            $kept = $keptJson;
        }

        // remove files that were present but not kept
        $toRemove = array_values(array_diff($galleryCurrent, $kept));
        foreach ($toRemove as $rm) {
            $full = public_path($rm);
            if (file_exists($full)) {
                @unlink($full);
            }
        }

        $galleryFinal = array_values($kept);

        // handle new uploads for gallery
        if ($request->hasFile('gallery_images')) {
            $files = $request->file('gallery_images');
            $uploadDir = public_path('uploads/gallery');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            foreach ($files as $f) {
                if ($f && $f->isValid()) {
                    $filename = time() . '_' . Str::random(8) . '.' . $f->getClientOriginalExtension();
                    $f->move($uploadDir, $filename);
                    $galleryFinal[] = 'uploads/gallery/' . $filename;
                }
            }
        }

        // save gallery paths as JSON
        $updateSetting('home_gallery_images', json_encode(array_values($galleryFinal)));
        // simpan FAQ
        $faqJson = $request->home_faq_json ?: '[]';
        $faqData = json_decode($faqJson, true);
        if (!is_array($faqData)) {
            return back()->with('error', 'Format FAQ tidak valid.');
        }

        $clean = [];
        foreach ($faqData as $item) {
            $q = trim($item['q'] ?? '');
            if ($q !== '') {
                $clean[] = [
                    'q' => $q,
                    'a' => trim($item['a'] ?? '')
                ];
            }
        }

        $updateSetting('home_faq', json_encode($clean));

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

}
