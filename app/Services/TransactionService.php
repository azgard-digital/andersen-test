<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\TransactionDTO;
use App\Enums\TransactionStatus;
use App\Exceptions\ResourceException;
use App\Helpers\Calculator;
use App\Interfaces\ITransactionService;
use App\Interfaces\IWalletService;
use App\Models\Transaction;
use App\Repository\TransactionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService implements ITransactionService
{
    private const COMPANY_FEE = 1.5;

    private $walletService;
    private $transactionRepository;

    public function __construct(IWalletService $walletService, TransactionRepository $transactionRepository)
    {
        $this->walletService = $walletService;
        $this->transactionRepository = $transactionRepository;
    }

    protected function calculateFee(int $amount): int
    {
        return (int)round(($amount * self::COMPANY_FEE) / 100);
    }

    protected function createUserTransaction(TransactionDTO $dto): bool
    {
        $model = new Transaction();

        $transaction = $model->fill([
            'user_id' => $dto->getUserId(),
            'wallet_id' => $dto->getWalletId(),
            'amount' => $dto->getAmount(),
            'fee' => $dto->getFee(),
            'status' => $dto->getResult(),
            'details' => ['from' => $dto->getFrom(), 'to' => $dto->getTo()]
        ]);

        return $transaction->save();
    }

    protected function processTransaction(string $from, string $to, Calculator $calculate): bool
    {
        try {

            DB::beginTransaction();
            $this->walletService->takeTransaction($from, $calculate);
            $this->walletService->putTransaction($to, $calculate);
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

    public function create(int $userId, string $from, string $to, int $amount): TransactionDTO
    {
        if (!$this->walletService->isWalletExist($userId, $from)) {
            throw new ResourceException('Invalid user wallet');
        }

        $fee = 0;

        if (!$this->walletService->isWalletExist($userId, $to)) {
            $fee = $this->calculateFee($amount);
        }

        $calculator = new Calculator($amount, $fee);
        $result = $this->processTransaction($from, $to, $calculator);
        $walletId = $this->walletService->getWalletIdByAddress($from, $userId);
        $status = ($result) ? TransactionStatus::value('success') : TransactionStatus::value('fail');

        $dto = new TransactionDTO(
            $from,
            $to,
            $amount,
            $fee,
            $status,
            $walletId,
            $userId
        );

        if (!$this->createUserTransaction($dto)) {
            throw new ResourceException('Transaction log has not been created');
        }

        return $dto;
    }

    public function getUserTransactions(int $userId): Collection
    {
        return $this->transactionRepository->getUserTransactions($userId);
    }
}
