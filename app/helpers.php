<?php

if (! function_exists('format_bytes')) {
    function format_bytes(int|float $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        $power = min($power, count($units) - 1);

        $bytes /= (1024 ** $power);

        return round($bytes, $precision) . ' ' . $units[$power];
    }
}

if (! function_exists('format_bps')) {
    function format_bps(int|float $bps, int $precision = 2): string
    {
        $units = ['bps', 'Kbps', 'Mbps', 'Gbps', 'Tbps'];

        $bps = max($bps, 0);
        $power = $bps > 0 ? floor(log($bps, 1000)) : 0;
        $power = min($power, count($units) - 1);

        $bps /= (1000 ** $power);

        return round($bps, $precision) . ' ' . $units[$power];
    }
}

if (! function_exists('format_uptime')) {
    function format_uptime(string $uptime): string
    {
        preg_match_all('/(\d+)([wdhms])/', $uptime, $matches, PREG_SET_ORDER);
    
        $days = 0;
        $h = $m = $s = 0;

        foreach ($matches as $match) {
            $val = (int)$match[1];
            $unit = $match[2];

            if ($unit === 'w') $days += $val * 7;
            elseif ($unit === 'd') $days += $val;
            elseif ($unit === 'h') $h = $val;
            elseif ($unit === 'm') $m = $val;
            elseif ($unit === 's') $s = $val;
        }

        // 2. Format output: %dd %02d:%02d:%02d
        return $days > 0
            ? sprintf("%dd %02d:%02d:%02d", $days, $h, $m, $s)
            : sprintf("%02d:%02d:%02d", $h, $m, $s);
    }
}

if (! function_exists('format_latency')) {
    function format_latency(int|float $ms): string
    {
        return round($ms, 2) . ' ms';
    }
}

if (! function_exists('format_packets')) {
    function format_packets(int $packets): string
    {
        if ($packets >= 1000000) {
            return round($packets / 1000000, 2) . ' Mpps';
        }

        if ($packets >= 1000) {
            return round($packets / 1000, 2) . ' Kpps';
        }

        return $packets . ' pps';
    }
}

if (! function_exists('format_exp')) {
    function format_exp($date)
    {
        return 'EXP|' . strtolower($date->format('M/d/Y H:i:s'));
    }
}