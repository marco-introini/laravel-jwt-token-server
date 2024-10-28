<?php

namespace App\Http\Controllers\Api\SimpleJWT;

use App\Helpers\JwtHelper;
use App\Helpers\SimpleJwtHelper;
use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SimpleJwtLoginController extends ApiController
{
    public function __invoke()
    {
        $email = request()->input('email');
        $password = request()->input('password');

        $user = User::where('email', $email)->first();

        if (is_null($user)) {
            return $this->error("User not found", 404);
        }

        if (!Hash::check($password, $user->password)) {
            return $this->error("Wrong Password", 403);
        }

        $token = $user->jwtTokens()->create();

        return $this->success("Login Successful", [
            'tokenHS256' => SimpleJwtHelper::createJwtHS256($token),
            'tokenRS256' => SimpleJwtHelper::createJwtRS256($token),
            'tokenES256' => SimpleJwtHelper::createJwtES256($token),
        ]);
    }
}
