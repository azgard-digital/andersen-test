<?php
declare(strict_types=1);

namespace App\Interfaces;

use App\Models\User;

interface IUserService
{
    /**
     * @param string $email
     * @param string $name
     * @param string $password
     * @return User
     */
    public function create(string $email, string $name, string $password): User;
}
