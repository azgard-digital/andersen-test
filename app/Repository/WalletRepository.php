<?php
declare(strict_types=1);

namespace App\Repository;

use App\Exceptions\ResourceException;
use App\Models\Wallet;
use App\Services\Payment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WalletRepository
{
    public function takeTransaction(string $from, Payment $payment): void
    {
        try {
            DB::beginTransaction();
            $walletFrom = Wallet::query()
                ->where('address', $from)
                ->lockForUpdate()
                ->first();

            $walletFromBalance = $walletFrom->balance;

            if ($walletFromBalance < $payment->calculateAmountWithFee()) {
                throw new ResourceException('Not enough money for transaction');
            }

            $walletFrom->balance = $payment->calculateFromBalance($walletFromBalance);
            $walletFrom->save();
            DB::commit();
        } catch (ResourceException $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new ResourceException('Reward has not been charged');
        }
    }

    public function putTransaction(string $to, Payment $payment): void
    {
        DB::transaction(function () use ($to, $payment) {
            $walletTo = Wallet::query()
                ->where('address', $to)
                ->lockForUpdate()
                ->first();

            $walletToBalance = $walletTo->balance;

            $walletTo->balance = $payment->calculateToBalance($walletToBalance);
            $walletTo->save();
        });
    }

    public function getTransactionsByAddress(string $address): Collection
    {
        return DB::table('wallets')
            ->join('transactions', 'wallets.id', '=', 'transactions.wallet_id')
            ->where('wallets.address', $address)
            ->get([
                'transactions.id',
                'transactions.created_at',
                'transactions.updated_at',
                'transactions.status',
                'transactions.amount',
                'transactions.fee'
            ]);
    }

    public function getWalletsCount(int $userId): int
    {
        return Wallet::query()->where('user_id', $userId)->count();
    }

    public function getUserWalletByAddress(string $address, int $userId): ?Wallet
    {
        return Wallet::query()
            ->where('user_id', $userId)
            ->where('address', $address)
            ->first();
    }

    public function isUserWalletExist(int $userId, string $address): bool
    {
        return Wallet::query()
            ->where('user_id', $userId)
            ->where('address', $address)
            ->exists();
    }
}
