<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App\Models
 * @property string $name
 * @property string $email
 * @property string $password
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $guarded = ['id', 'password'];
    protected $hidden = ['password'];
}
