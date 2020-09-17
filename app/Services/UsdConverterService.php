<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\IRate;

class UsdConverterService
{
    private $rate;

    public function __construct(IRate $rate)
    {
        $this->rate = $rate;
    }

    public function convert(int $balance): string
    {
        if ($this->rate->isConvertible()) {
            $calculate = ($balance / 100000000) * $this->rate->getUsd();
            return number_format((float)$calculate, 4, '.', '');
        }

        return '';
    }
}
