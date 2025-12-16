<?php
// app/helpers.php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    /**
     * Get setting by key with optional default. Cached for 5 minutes.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        $cacheKey = 'setting_'.$key;
        return Cache::remember($cacheKey, 300, function() use ($key, $default) {
            $s = Setting::where('key', $key)->first();
            if (! $s) return $default;
            return $s->value ?? $default;
        });
    }
}