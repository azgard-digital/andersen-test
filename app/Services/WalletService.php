<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\WalletDTO;
use App\Exceptions\ResourceException;
use App\Interfaces\IWalletService;
use App\Models\User;
use App\Models\Wallet;
use App\Repository\WalletRepository;
use Illuminate\Support\Collection;

class WalletService implements IWalletService
{
    private const WALLETS_LIMIT = 9;
    private const DEFAULT_BALANCE = 100000000;

    private $currencyService;
    private $walletRepository;

    public function __construct(
        CurrencyService $currencyService,
        WalletRepository $walletRepository
    ) {
        $this->currencyService = $currencyService;
        $this->walletRepository = $walletRepository;
    }

    public function isLimited(int $userId): bool
    {
        return ($this->walletRepository->getWalletsCount($userId) >= self::WALLETS_LIMIT);
    }

    protected function createWalletAddress(string $prefix): string
    {
        return md5(uniqid($prefix, true));
    }

    protected function createUserWallet(int $userId, string $address): bool
    {
        $model = new Wallet();

        $wallet = $model->fill([
            'balance' => self::DEFAULT_BALANCE,
            'user_id' => $userId,
            'address' => $address
        ]);

        return $wallet->save();
    }

    public function create(User $user): WalletDTO
    {
        $address = $this->createWalletAddress((string) $user->id);

        if (!$this->createUserWallet($user->id, $address)) {
            throw new ResourceException('Wallet has not been created');
        }

        return new WalletDTO(
            $address,
            $this->currencyService->convertToBtc(self::DEFAULT_BALANCE),
            $this->currencyService->convertToUsd(self::DEFAULT_BALANCE)
        );
    }

    public function getWalletByAddress(string $address, User $user): WalletDTO
    {
        $wallet = $this->walletRepository->getUserWalletByAddress($address, $user->id);

        if (!$wallet) {
            throw new ResourceException('Wallet has not been found');
        }

        return new WalletDTO(
            $address,
            $this->currencyService->convertToBtc($wallet->balance),
            $this->currencyService->convertToUsd($wallet->balance)
        );
    }

    public function isWalletExist(int $userId, string $address): bool
    {
        return $this->walletRepository->isUserWalletExist($userId, $address);
    }

    public function putTransaction(string $to, Payment $payment): void
    {
        $this->walletRepository->putTransaction($to, $payment);
    }

    public function takeTransaction(string $from, Payment $payment): void
    {
        $this->walletRepository->takeTransaction($from, $payment);
    }

    public function getWalletIdByAddress(string $address, int $userId): ?int
    {
        $wallet = $this->walletRepository->getUserWalletByAddress($address, $userId);

        if (!$wallet) {
            return null;
        }

        return $wallet->getAttribute('id');
    }

    public function getTransactionsByAddress(string $address): Collection
    {
        return $this->walletRepository->getTransactionsByAddress($address);
    }
}
