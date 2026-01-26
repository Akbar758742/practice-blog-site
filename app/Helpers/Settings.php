<?php

namespace App\Helpers;

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Cache;

class Settings
{
    /**
     * Get all settings with caching
     */
    public static function get($key = null, $default = null)
    {
        $settings = Cache::remember('general_settings', 3600, function () {
            return GeneralSetting::first();
        });

        if (is_null($settings)) {
            return $default;
        }

        if (is_null($key)) {
            return $settings;
        }

        return $settings->$key ?? $default;
    }

    /**
     * Get site logo URL
     */
    public static function logo()
    {
        $logo = self::get('site_logo');
        if ($logo) {
            return asset('storage/' . $logo);
        }
        return asset('backend/vendors/images/deskapp-logo.svg');
    }

    /**
     * Get site logo (white version for dark backgrounds)
     */
    public static function logoWhite()
    {
        $logo = self::get('site_logo');
        if ($logo) {
            return asset('storage/' . $logo);
        }
        return asset('backend/vendors/images/deskapp-logo-white.svg');
    }

    /**
     * Get favicon URL
     */
    public static function favicon()
    {
        $favicon = self::get('site_favicon');
        if ($favicon) {
            return asset('storage/' . $favicon);
        }
        return asset('backend/vendors/images/favicon-32x32.png');
    }

    /**
     * Get site name
     */
    public static function siteName()
    {
        return self::get('site_name', 'Blog Admin');
    }

    /**
     * Clear settings cache (call after updating settings)
     */
    public static function clearCache()
    {
        Cache::forget('general_settings');
    }
}
