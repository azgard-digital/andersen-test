<?php
declare(strict_types=1);

namespace App\Helpers;

final class Calculator
{
    public static function calculateFee(int $amount, float $fee): int
    {
        return (int) (($amount * $fee) / 100);
    }
}
