<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Interfaces\ICalculator;
use App\Traits\CalculateTrait;

final class PaidCalculator implements ICalculator
{
    use CalculateTrait;

    const COMPANY_FEE = 1.5;

    private $amount;
    private $fee = 0;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
        $this->fee = $this->calculateFee($amount);
    }

    private function calculateFee(int $amount): int
    {
        return (int)round(($amount * self::COMPANY_FEE) / 100);
    }
}