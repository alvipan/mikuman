<?php

use App\Services\Settings\SettingService;
use Carbon\CarbonInterface;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return app(SettingService::class)
            ->get($key, $default);
    }
}

if (! function_exists('money')) {
    function money(float|int|null $amount): string
    {
        $amount ??= 0;

        return sprintf(
            '%s %s',
            setting('currency_symbol', 'Rp'),
            number_format($amount, 0, ',', '.')
        );
    }
}

if (! function_exists('datetime')) {
    function datetime(
        CarbonInterface|string|null $date,
        string $format = 'd M Y H:i'
    ): string {
        if (! $date) {
            return '-';
        }

        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        return $date
            ->timezone(setting('timezone', config('app.timezone')))
            ->format($format);
    }
}