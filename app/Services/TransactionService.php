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
use App\Models\User;
use App\Repository\TransactionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService implements ITransactionService
{
    private const COMPANY_FEE = 1.5;

    private $walletService;
    private $transactionRepository;
    private $payment;

    public function __construct(
        IWalletService $walletService,
        TransactionRepository $transactionRepository
    ) {
        $this->walletService = $walletService;
        $this->transactionRepository = $transactionRepository;
        $this->payment = new Payment();
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

    protected function processTransaction(string $from, string $to, Payment $payment): bool
    {
        try {
            DB::beginTransaction();
            $this->walletService->takeTransaction($from, $payment);
            $this->walletService->putTransaction($to, $payment);
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

    public function create(User $user, string $from, string $to, int $amount): TransactionDTO
    {
        $userId = $user->id;

        if (!$this->walletService->isWalletExist($userId, $from)) {
            throw new ResourceException('Invalid user wallet');
        }

        $this->payment->setAmount($amount);

        if (!$this->walletService->isWalletExist($userId, $to)) {
            $this->payment->setFee(Calculator::calculateFee($amount, self::COMPANY_FEE));
        }

        $result = $this->processTransaction($from, $to, $this->payment);
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

    public function getUserTransactions(User $user): Collection
    {
        return $this->transactionRepository->getUserTransactions($user->id);
    }
}
