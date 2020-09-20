<?php
declare(strict_types=1);

namespace App\Repository;

use App\Exceptions\ResourceException;
use App\Helpers\Calculator;
use App\Models\Wallet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletRepository
{
    public function process(string $from, string $to, Calculator $calculate): bool
    {
        try {
            DB::beginTransaction();
            $walletFrom = Wallet::query()
                ->where('address', $from)
                ->lockForUpdate()
                ->first();

            $walletFromBalance = $walletFrom->balance;

            if ($walletFromBalance < $calculate->calculateAmountWithFee()) {
                throw new ResourceException('Not enough money for transaction');
            }

            $walletTo = Wallet::query()
                ->where('address', $to)
                ->lockForUpdate()
                ->first();

            $walletToBalance = $walletTo->balance;

            $walletFrom->balance = $calculate->calculateFromBalance($walletFromBalance);
            $walletTo->balance = $calculate->calculateToBalance($walletToBalance);

            $walletFrom->save();
            $walletTo->save();

            DB::commit();

            return true;
        } catch (ResourceException $e) {
            DB::rollBack();
            throw new ResourceException($e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

        return false;
    }

    public function getTransactionsByAddress(string $address): Collection
    {
        return DB::table('wallets')
            ->join('transactions', 'wallets.id', '=', 'transactions.wallet_id')
            ->where('wallets.address', $address)
            ->select(['transactions.*'])
            ->get();
    }

    public function getWalletsCount(int $userId): int
    {
        return Wallet::query()->where('user_id', $userId)->count();
    }

    public function getUserWalletByAddress(string $address, int $userId): ?Wallet
    {
        $wallet = Wallet::query()
            ->where('user_id', $userId)
            ->where('address', $address)
            ->first();

        if (($wallet instanceof Wallet) === false) {
            return null;
        }

        return $wallet;
    }

    public function isUserWalletExist(int $userId, string $address): bool
    {
        return Wallet::query()
            ->where('user_id', $userId)
            ->where('address', $address)
            ->exists();
    }
}
