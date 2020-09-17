<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\WalletDTO;
use App\Exceptions\ResourceException;
use App\Interfaces\ICalculator;
use App\Interfaces\IWalletService;
use App\Models\Wallet;
use App\Repository\WalletRepository;
use Illuminate\Support\Collection;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class WalletService implements IWalletService
{
    const WALLETS_LIMIT = 9;
    const DEFAULT_BALANCE = 100000000;

    private $wallet;

    private $btcConverterService;

    private $usdConverterService;

    private $walletRepository;

    public function __construct(
        Wallet $wallet,
        BtcConverterService $btcConverterService,
        UsdConverterService $usdConverterService,
        WalletRepository $walletRepository
    ) {
        $this->wallet = $wallet;
        $this->btcConverterService = $btcConverterService;
        $this->usdConverterService = $usdConverterService;
        $this->walletRepository = $walletRepository;
    }

    public function isLimited(int $userId): bool
    {
        if ($this->getAmount($userId) < self::WALLETS_LIMIT) {
            return true;
        }

        return false;
    }

    protected function getAmount(int $userId): int
    {
        return (int)$this->wallet->query()->where('user_id', $userId)->count();
    }

    public function create(int $userId): WalletDTO
    {
        if ($this->isLimited($userId)) {
            new ThrottleRequestsException('Too many wallets');
        }

        $address = md5(uniqid((string)$userId));

        $this->wallet->fill([
            'balance' => self::DEFAULT_BALANCE,
            'user_id' => $userId,
            'address' => $address
        ])->save();

        return new WalletDTO(
            $address,
            $this->btcConverterService->convert(self::DEFAULT_BALANCE),
            $this->usdConverterService->convert(self::DEFAULT_BALANCE)
        );
    }

    public function getWalletByAddress(string $address, int $userId): WalletDTO
    {
        $wallet = $this->wallet->query()
            ->select('balance')
            ->where('user_id', $userId)
            ->where('address', $address)
            ->first();

        if (!$wallet) {
            throw new ResourceException('Wallet has not been found!');
        }

        return new WalletDTO(
            $address,
            $this->btcConverterService->convert($wallet->balance),
            $this->usdConverterService->convert($wallet->balance)
        );
    }

    public function isWalletExist(int $userId, string $address): bool
    {
        return $this->wallet->query()
            ->where('user_id', $userId)
            ->where('address', $address)
            ->exists();
    }

    public function processTransaction(string $from, string $to, ICalculator $calculate): bool
    {
        return $this->walletRepository->process($from, $to, $calculate);
    }

    public function getWalletIdByAddress(string $address): ?int
    {
        $wallet = $this->wallet->query()
            ->select(['id'])
            ->where('address', $address)
            ->first();

        return $wallet->getAttribute('id');
    }

    public function getTransactionsByAddress(string $address): Collection
    {
        return $this->walletRepository->getTransactionsByAddress($address);
    }
}
