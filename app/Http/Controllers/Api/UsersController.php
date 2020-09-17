<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\FormRequests\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Interfaces\IUserService;
use Laravel\Passport\PersonalAccessTokenResult;

class UsersController extends Controller
{
    private $user;

    public function __construct(IUserService $user)
    {
        $this->user = $user;
    }

    /**
     * Create a new personal access token for the user.
     *
     * @param \Illuminate\Http\Request $request
     * @return PersonalAccessTokenResult
     */
    public function store(RegisterRequest $request): PersonalAccessTokenResult
    {
        $user = $this->user->create(
            $request->get('email'),
            $request->get('name'),
            $request->get('password')
        );

        return $user->createToken($request->name);
    }
}

