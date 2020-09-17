<?php

namespace App\Services;

use App\Interfaces\IUserService;
use App\Models\User;

class UserService implements IUserService
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(string $email, string $name, string $password): User
    {
        $this->user->fill([
            'email' => $email,
            'password' => bcrypt($password),
            'name' => $name
        ])->save();

        return $this->user;
    }
}
