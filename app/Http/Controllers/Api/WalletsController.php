<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\IWalletService;
use App\Resources\TransactionsResource;
use App\Resources\WalletResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WalletsController extends Controller
{
    private $wallet;

    public function __construct(IWalletService $wallet)
    {
        $this->wallet = $wallet;
    }

    public function store(Request $request): JsonResource
    {
        return new WalletResource(
            $this->wallet->create(
                $request->user()->id
            )
        );
    }

    public function show(string $address, Request $request): JsonResource
    {
        return new WalletResource(
            $this->wallet->getWalletByAddress(
                $address,
                $request->user()->id
            )
        );
    }

    public function transactions(string $address): ResourceCollection
    {
        return new TransactionsResource(
            $this->wallet->getTransactionsByAddress(
                $address
            )
        );
    }
}
