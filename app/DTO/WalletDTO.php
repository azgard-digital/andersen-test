<?php
declare(strict_types=1);

namespace App\DTO;

final class WalletDTO
{
    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $btcBalance;

    /**
     * @var string
     */
    private $usdBalance;

    public function __construct(string $address, string $btcBalance, string $usdBalance)
    {
        $this->address = $address;
        $this->btcBalance = $btcBalance;
        $this->usdBalance = $usdBalance;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getBtcBalance(): string
    {
        return $this->btcBalance;
    }

    /**
     * @return string
     */
    public function getUsdBalance(): string
    {
        return $this->usdBalance;
    }
}
