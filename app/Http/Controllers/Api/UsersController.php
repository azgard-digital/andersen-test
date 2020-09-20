<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Interfaces\IUserService;
use Laravel\Passport\PersonalAccessTokenResult;

class UsersController extends Controller
{
    private $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(RegisterRequest $request): PersonalAccessTokenResult
    {
        $user = $this->userService->create(
            $request->get('email'),
            $request->get('name'),
            $request->get('password')
        );

        return $user->createToken($request->name);
    }
}

