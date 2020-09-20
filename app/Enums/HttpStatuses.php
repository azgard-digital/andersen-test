<?php
declare(strict_types=1);

namespace App\Enums;

class HttpExceptions extends Enum
{
    private const UNPROCESSABLE_EXCEPTION = 422;

    public static function titles(): array
    {
        return [
            self::UNPROCESSABLE_EXCEPTION => 'unprocessable',
        ];
    }
}
