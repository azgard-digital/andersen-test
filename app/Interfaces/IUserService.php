<?php
declare(strict_types=1);

namespace App\Interfaces;

use App\Models\User;

interface IUserService
{
    public function create(string $email, string $name, string $password): User;
}
