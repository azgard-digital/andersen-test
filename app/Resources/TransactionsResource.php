<?php
declare(strict_types=1);

namespace App\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionsResource extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection
        ];
    }
}
