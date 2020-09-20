<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\Currency;
use App\Interfaces\IRate;

class CurrencyService
{
    private $rate;

    public function __construct(IRate $rate)
    {
        $this->rate = $rate;
    }

    public function convertToUsd(int $balance): string
    {
        if ($this->rate->isConvertible()) {
            $calculate = ($balance / Currency::value('btc_unit')) * $this->rate->getUsd();
            return number_format((float)$calculate, 4, '.', '');
        }

        return '';
    }

    public function convertToBtc(int $balance): string
    {
        return number_format(
            (float)($balance / Currency::value('btc_unit')),
            4, '.', ''
        );
    }
}
