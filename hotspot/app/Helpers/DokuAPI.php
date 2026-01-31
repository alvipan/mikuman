<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DokuAPI
{
    private static $url = 'https://api-sandbox.doku.com';
    private static $client_id = 'BRN-0207-1764038583990';
    private static $secret_key = 'SK-xnhEnziyHlvVgh8Cs2Sv';

    public static function request($path, $body) 
    {
        $now = Carbon::now();
        $request_id = 'INV-'.time();
        $request_timestamp = $now->format('c');

        $signature = self::generateSignature(
            self::$client_id,
            self::$secret_key,
            $request_id,
            $request_timestamp,
            $path,
            $body
        );

        $headers = [
            'client-id' => self::$client_id,
            'request-id' => $request_id,
            'request-timestamp' => $request_timestamp,
            'signature' => $signature
        ];
        
        $response = Http::withOptions(['verify' => false])
            ->withHeaders($headers)
            ->post(self::$url.$path, $body);

        return $response;
    }

    private static function generateSignature(
        $client_id,
        $secret_key,
        $request_id, 
        $request_timestamp,
        $request_target,
        $request_body
    ) {
        $digestSHA256 = hash('sha256', json_encode($request_body), true);
        $digestBase64 = base64_encode($digestSHA256);
        $rawSignature = 'Client-Id:' . $client_id . "\n" .
                        'Request-Id:' . $request_id . "\n" .
                        'Request-Timestamp:' . $request_timestamp . "\n" .
                        'Request-Target:' . $request_target . "\n" .
                        'Digest:' . $digestBase64;

        $signatureHmacSha256 = hash_hmac('sha256', $rawSignature, $secret_key, true);
        $signatureBase64 = base64_encode($signatureHmacSha256);

        return 'HMACSHA256=' . $signatureBase64;
    }
}