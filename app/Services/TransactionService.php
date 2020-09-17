<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\TransactionDTO;
use App\Enums\TransactionStatus;
use App\Exceptions\ResourceException;
use App\Factories\TransactionCalculatorFactory;
use App\Interfaces\ITransactionService;
use App\Interfaces\IWalletService;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

class TransactionService implements ITransactionService
{
    private $walletService;

    private $transaction;

    public function __construct(
        IWalletService $walletService,
        Transaction $transaction
    ) {
        $this->walletService = $walletService;
        $this->transaction = $transaction;
    }

    public function create(int $userId, string $from, string $to, int $amount): TransactionDTO
    {
        if (!$this->walletService->isWalletExist($userId, $from)) {
            throw new ResourceException('Invalid user wallet');
        }

        $factory = new TransactionCalculatorFactory($amount);

        if ($this->walletService->isWalletExist($userId, $to)) {
            $calculate = $factory->getFreeCalculator();
        } else {
            $calculate = $factory->getPaidCalculator();
        }

        $result = $this->walletService->processTransaction($from, $to, $calculate);
        $walletId = $this->walletService->getWalletIdByAddress($from);
        $status = ($result) ? TransactionStatus::value('success') : TransactionStatus::value('fail');

        $this->transaction->fill([
            'user_id' => $userId,
            'wallet_id' => $walletId,
            'amount' => $amount,
            'fee' => $calculate->getFee(),
            'status' => $status,
            'details' => ['from' => $from, 'to' => $to]
        ])->save();

        return new TransactionDTO($from, $to, $amount, $calculate->getFee(), $status);
    }

    public function getUserTransactions(int $userId): Collection
    {
        return $this->transaction->query()
            ->where('user_id', $userId)
            ->with('wallet')
            ->get();
    }
}
