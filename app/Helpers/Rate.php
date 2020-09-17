<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Interfaces\IRate;
use Illuminate\Support\Facades\Cache;

final class Rate implements IRate
{
    const CACHE_KEY = 'rate_store_cache';
    const CACHE_RATE_LIFETIME = 86400;

    private $usdRate;

    public function __construct()
    {
        $this->setUsdRate();
    }

    /**
     * @return void
     */
    private function setUsdRate()
    {
        $content = @json_decode($this->loadContent(), true);

        if ($content && isset($content['bpi']['USD']['rate_float'])) {
            $this->usdRate = (float)$content['bpi']['USD']['rate_float'];
        }
    }

    private function loadContent(): ?string
    {
        if (Cache::has(self::CACHE_KEY)) {
            return Cache::get(self::CACHE_KEY);
        }

        $content = @file_get_contents('http://api.coindesk.com/v1/bpi/currentprice/USD.json');

        if ($content) {
            Cache::add(self::CACHE_KEY, $content, self::CACHE_RATE_LIFETIME);
            return $content;
        }

        return null;
    }

    public function isConvertible(): bool
    {
        return is_float($this->usdRate);
    }

    public function getUsd(): ?float
    {
        return $this->usdRate;
    }
}
