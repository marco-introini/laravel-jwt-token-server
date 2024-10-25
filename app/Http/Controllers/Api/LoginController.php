<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends ApiController
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
            'token' => $token->getJwtHS256(),
        ]);
    }
}
