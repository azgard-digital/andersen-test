<?php
declare(strict_types=1);

namespace App\Interfaces;

use App\DTO\TransactionDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ITransactionService
{
    /**
     * @param User $user
     * @param string $from
     * @param string $to
     * @param int $amount
     * @return TransactionDTO
     */
    public function create(User $user, string $from, string $to, int $amount): TransactionDTO;

    /**
     * @param User $user
     * @return Collection
     */
    public function getUserTransactions(User $user): Collection;
}
