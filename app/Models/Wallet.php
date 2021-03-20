<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Wallet
 *
 * @package App\Modules\Wallets\Models
 * @property int $balance
 * @property string $address
 * @property int $user_id
 */
class Wallet extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'address',
        'balance',
        'user_id',
    ];
    protected $guarded = ['id', 'balance'];
}
