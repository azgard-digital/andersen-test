<?php
declare(strict_types=1);

namespace App\Enums;

class ExceptionErrors extends Enum
{
    const RESOURCE_ERROR = 422;

    public static function titles(): array
    {
        return [
            self::RESOURCE_ERROR => 'unprocessable',
        ];
    }
}
