<?php
declare(strict_types=1);

namespace App\Enums;

class Enum
{
    public static function titles(): array
    {
        return [];
    }

    public static function title(int $type, $unknown = null): ?string
    {
        $titles = static::titles();

        if (isset($titles[$type])) {
            return $titles[$type];
        }

        return $unknown;
    }

    public static function value(string $title, $unknown = null): ?int
    {
        $titles = static::titles();

        if (($key = array_search($title, $titles)) !== false) {
            return (int)$key;
        }

        return $unknown;
    }
}
