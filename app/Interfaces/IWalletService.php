<?php


namespace App\Interfaces;

use App\DTO\WalletDTO;
use App\Models\User;
use App\Services\Payment;
use Illuminate\Support\Collection;

interface IWalletService
{
    /**
     * @param int $userId
     * @return bool
     */
    public function isLimited(int $userId): bool;

    /**
     * @param User $user
     * @return WalletDTO
     */
    public function create(User $user): WalletDTO;

    /**
     * @param string $address
     * @param int $userId
     * @return WalletDTO
     */
    public function getWalletByAddress(string $address, User $user): WalletDTO;

    /**
     * @param int $userId
     * @param string $address
     * @return bool
     */
    public function isWalletExist(int $userId, string $address): bool;

    /**
     * @param string $to
     * @param Payment $payment
     */
    public function putTransaction(string $to, Payment $payment): void;

    /**
     * @param string $from
     * @param Payment $payment
     */
    public function takeTransaction(string $from, Payment $payment): void;

    /**
     * @param string $address
     * @param int $userId
     * @return int|null
     */
    public function getWalletIdByAddress(string $address, int $userId): ?int;

    /**
     * @param string $address
     * @return Collection
     */
    public function getTransactionsByAddress(string $address): Collection;
}
