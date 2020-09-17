<?php
declare(strict_types=1);

namespace App\Interfaces;


interface ICalculator
{
    public function calculateFromBalance(int $balance): int;

    public function calculateToBalance(int $balance): int;

    public function calculateAmountWithFee(): int;

    public function getFee(): int;
}
