<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * @package App\Modules\Users\Models
 * @property int status
 * @property int user_id
 * @property int wallet_id
 * @property int $amount
 * @property int $fee
 * @property array $details
 */
class Transaction extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'user_id',
        'wallet_id',
        'status',
        'amount',
        'fee',
        'details'
    ];

    /**
     * @inheritdoc
     */
    protected $casts = [
        'details' => 'array'
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'id', 'wallet_id');
    }
}
