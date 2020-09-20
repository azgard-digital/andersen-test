<?php
declare(strict_types=1);

namespace App\Enums;

class HttpStatuses extends Enum
{
    private const UNPROCESSABLE_STATUS = 422;
    private const SUCCESS_STATUS = 200;
    private const FORBIDDEN_STATUS = 403;

    public static function titles(): array
    {
        return [
            self::UNPROCESSABLE_STATUS => 'unprocessable',
            self::SUCCESS_STATUS => 'success',
            self::FORBIDDEN_STATUS => 'forbidden'
        ];
    }
}
