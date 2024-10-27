<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelpers;

class JwtCheckController extends ApiController
{
    public function checkHS256()
    {
        $token = request()->bearerToken();
        $payload = JwtHelpers::decodeJwtHS256($token);

        if (!$payload) {
            return $this->error('Invalid token', 403);
        }

        if (time() > $payload['exp']) {
            return $this->error('Token expired', 403);
        }

        return $this->success("Token is valid",$payload);
    }

    public function checkRS256()
    {
        $token = request()->bearerToken();
        $payload = JwtHelpers::decodeJwtRS256($token);

        if (!$payload) {
            return $this->error('Invalid token', 403);
        }

        if (time() > $payload['exp']) {
            return $this->error('Token expired', 403);
        }

        return $this->success("Token is valid",$payload);
    }

    public function checkES256()
    {
        $token = request()->bearerToken();
        $payload = JwtHelpers::decodeJwtES256($token);

        if (!$payload) {
            return $this->error('Invalid token', 403);
        }

        if (time() > $payload['exp']) {
            return $this->error('Token expired', 403);
        }

        return $this->success("Token is valid",$payload);
    }
}
