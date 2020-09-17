<?php
declare(strict_types=1);

namespace App\Factories;

use App\Helpers\FreeCalculator;
use App\Helpers\PaidCalculator;
use App\Interfaces\ICalculator;

class TransactionCalculatorFactory
{
    /**
     * @var int
     */
    private $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * Create paid
     * @return ICalculator
     */
    public function getPaidCalculator(): ICalculator
    {
        return new PaidCalculator($this->amount);
    }

    /**
     * Create free
     * @return ICalculator
     */
    public function getFreeCalculator(): ICalculator
    {
        return new FreeCalculator($this->amount);
    }
}
