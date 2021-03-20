<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Exceptions\WalletsLimitException;
use App\Http\Controllers\Controller;
use App\Interfaces\IWalletService;
use App\Resources\TransactionsResource;
use App\Resources\WalletResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WalletsController extends Controller
{
    private $walletService;

    public function __construct(IWalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function store(Request $request): JsonResource
    {
        $user = $request->user();

        if ($this->walletService->isLimited($user->id)) {
            throw new WalletsLimitException('Too many wallets');
        }

        return new WalletResource(
            $this->walletService->create($user)
        );
    }

    public function show(string $address, Request $request): JsonResource
    {
        return new WalletResource(
            $this->walletService->getWalletByAddress(
                $address,
                $request->user()
            )
        );
    }

    public function transactions(string $address): ResourceCollection
    {
        return new TransactionsResource(
            $this->walletService->getTransactionsByAddress(
                $address
            )
        );
    }
}
