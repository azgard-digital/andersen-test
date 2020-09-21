<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ResourceException;
use App\Interfaces\IUserService;
use App\Models\User;

class UserService implements IUserService
{
    public function create(string $email, string $name, string $password): User
    {
        $model = new User();

        $model->fill([
            'email' => $email,
            'name' => $name
        ]);

        $model->password = bcrypt($password);

        if (!$model->save()) {
            throw new ResourceException('User has not been created');
        }

        return $model;
    }
}
