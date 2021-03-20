<?php
declare(strict_types=1);

namespace App\Services;

class Payment
{
    private $amount = 0;
    private $fee = 0;

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getFee(): int
    {
        return $this->fee;
    }

    public function setFee(int $fee): void
    {
        $this->fee = $fee;
    }

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
}
