<?php
declare(strict_types=1);

namespace App\Enums;

class TransactionStatus extends Enum
{
    private const SUCCESS = 1;
    private const FAIL = 2;

    public static function titles(): array
    {
        return [
            self::FAIL => 'fail',
            self::SUCCESS => 'success'
        ];
    }
}
