<?php


namespace App\Interfaces;

use App\DTO\WalletDTO;
use Illuminate\Support\Collection;

interface IWalletService
{
    /**
     * @param int $userId
     * @return bool
     */
    public function isLimited(int $userId): bool;

    /**
     * @param int $userId
     * @return WalletDTO
     */
    public function create(int $userId): WalletDTO;

    /**
     * @param string $address
     * @param int $userId
     * @return WalletDTO
     */
    public function getWalletByAddress(string $address, int $userId): WalletDTO;

    /**
     * @param int $userId
     * @param string $address
     * @return bool
     */
    public function isWalletExist(int $userId, string $address): bool;

    /**
     * @param string $from
     * @param string $to
     * @param ICalculator $calculate
     * @return bool
     */
    public function processTransaction(string $from, string $to, ICalculator $calculate): bool;

    /**
     * @param string $address
     * @return int|null
     */
    public function getWalletIdByAddress(string $address): ?int;

    /**
     * @param string $address
     * @return Collection
     */
    public function getTransactionsByAddress(string $address): Collection;
}
