<?php
declare(strict_types=1);

namespace App\Repository;

use App\Exceptions\ResourceException;
use App\Interfaces\ICalculator;
use App\Models\Wallet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletRepository
{
    private $model;

    public function __construct(Wallet $model)
    {
        $this->model = $model;
    }

    public function process(string $from, string $to, ICalculator $calculate): bool
    {
        try {
            DB::beginTransaction();
            $walletFrom = $this->model->query()
                ->where('address', $from)
                ->lockForUpdate()
                ->first();

            $walletFromBalance = $walletFrom->balance;

            if ($walletFromBalance < $calculate->calculateAmountWithFee()) {
                throw new ResourceException('Not enough money for transaction');
            }

            $walletTo = $this->model->query()
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
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            if ($e instanceof ResourceException) {
                throw new ResourceException($e->getMessage());
            }
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
}
