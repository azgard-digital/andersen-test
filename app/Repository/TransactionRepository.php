<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository
{
    public function getUserTransactions(int $userId): Collection
    {
        return Transaction::query()
            ->where('user_id', $userId)
            ->get([
                'id',
                'created_at',
                'updated_at',
                'status',
                'amount',
                'fee',
                'details'
            ]);
    }
}
