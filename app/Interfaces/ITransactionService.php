<?php
declare(strict_types=1);

namespace App\Interfaces;

use App\DTO\TransactionDTO;
use Illuminate\Database\Eloquent\Collection;

interface ITransactionService
{
    /**
     * @param int $userId
     * @param string $from
     * @param string $to
     * @param int $amount
     * @return TransactionDTO
     */
    public function create(int $userId, string $from, string $to, int $amount): TransactionDTO;

    /**
     * @param int $userId
     * @return Collection
     */
    public function getUserTransactions(int $userId): Collection;
}
