<?php
declare(strict_types=1);

namespace App\Enums;

class Currency extends Enum
{
    private const BTC_UNIT = 100000000;

    public static function titles(): array
    {
        return [
            self::BTC_UNIT => 'btc_unit'
        ];
    }
}
