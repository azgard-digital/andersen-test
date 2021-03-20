<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\IUserService;
use App\Models\User;

class UserService implements IUserService
{
    public function create(string $email, string $name, string $password): User
    {
        return User::create([
            'email' => $email,
            'name' => $name,
            'password' => bcrypt($password)
        ]);
    }
}
