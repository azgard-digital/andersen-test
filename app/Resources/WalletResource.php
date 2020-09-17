<?php
declare(strict_types=1);

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * @inheritDoc
     */
    public function toArray($request): array
    {
        return [
            'address' => $this->resource->getAddress(),
            'btc' => $this->resource->getBtcBalance(),
            'usd' => $this->resource->getUsdBalance()
        ];
    }
}
