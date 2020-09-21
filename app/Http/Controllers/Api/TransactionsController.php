<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionCreateRequest;
use App\Interfaces\ITransactionService;
use App\Resources\TransactionResource;
use App\Resources\TransactionsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionsController extends Controller
{
    private $transactionService;

    public function __construct(ITransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function store(TransactionCreateRequest $request): JsonResource
    {
        return new TransactionResource(
            $this->transactionService->create(
                (int)$request->user()->id,
                $request->json('wallets.from'),
                $request->json('wallets.to'),
                (int)$request->get('amount')
            )
        );
    }

    public function index(Request $request): ResourceCollection
    {
        return new TransactionsResource(
            $this->transactionService->getUserTransactions(
                $request->user()->id
            )
        );
    }
}
