<?php

namespace App\Services\Voucher;

class VoucherService
{
    protected string $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    public function generateCode(int $length = 6): string
    {
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $this->characters[random_int(0, strlen($this->characters) - 1)];
        }

        return $code;
    }

    public function generateBatch(int $qty, int $length = 6): array
    {
        $codes = [];

        for ($i = 0; $i < $qty; $i++) {
            $codes[] = $this->generateCode($length);
        }

        return $codes;
    }
}