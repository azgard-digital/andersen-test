<?php
declare(strict_types=1);

namespace App\Services;

class BtcConverterService
{
    public function convert(int $balance): string
    {
        return number_format((float)($balance / 100000000), 4, '.', '');
    }
}
