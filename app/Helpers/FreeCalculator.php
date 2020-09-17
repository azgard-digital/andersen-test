<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Interfaces\ICalculator;
use App\Traits\CalculateTrait;

final class FreeCalculator implements ICalculator
{
    use CalculateTrait;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var int
     */
    private $fee = 0;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }
}
