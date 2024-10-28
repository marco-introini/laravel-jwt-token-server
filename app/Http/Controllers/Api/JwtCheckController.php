<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;

class JwtCheckController extends ApiController
{
    public function checkHS256()
    {
        $token = request()->bearerToken();
        $payload = JwtHelper::decodeJwtHS256($token);

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
        dump($token);
        $payload = JwtHelper::decodeJwtRS256($token);

        if (!$payload) {
            return $this->error('Invalid token', 403);
        }

        if (time() > $payload['exp']) {
            return $this->error('Token expired', 403);
        }

        return $this->success("Token is valid",$payload);
    }

}
