<?php

namespace App\Services\Settings;

use App\Models\Setting;

class SettingService
{
    public function get(string $key, mixed $default = null): mixed
    {
        return cache()->rememberForever(
            "setting.{$key}",
            fn () => Setting::where('key', $key)
                ->value('value')
                ?? $default
        );
    }

    public function set(string $key, mixed $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        cache()->forget("setting.{$key}");
    }
}