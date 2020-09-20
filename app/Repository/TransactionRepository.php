<?php


namespace App\Repository;


use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository
{
    public function getUserTransactions(int $userId): Collection
    {
        return Transaction::query()
            ->where('user_id', $userId)
            ->with('wallet')
            ->get();
    }
}
