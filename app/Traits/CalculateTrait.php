<?php
declare(strict_types=1);

namespace App\Traits;

trait CalculateTrait
{
    public function calculateFromBalance(int $balance): int
    {
        return $balance - ($this->amount + $this->fee);
    }

    public function calculateToBalance(int $balance): int
    {
        return $balance + $this->amount;
    }

    public function calculateAmountWithFee(): int
    {
        return $this->fee + $this->amount;
    }

    public function getFee(): int
    {
        return $this->fee;
    }
}
