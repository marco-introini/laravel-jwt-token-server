<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelpers;
use App\Http\Controllers\Controller;

class JwtCheckController extends ApiController
{
    public function checkHS256()
    {
        $token = request()->bearerToken();
        $payload = JwtHelpers::decodeJwtHS256($token);

        if (!$payload) {
            return $this->error('Invalid token', 403);
        }

        return $this->success("Token is valid",$payload);
    }
}
