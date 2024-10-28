<?php

namespace App\Http\Controllers\Api\SimpleJWT;

use App\Helpers\SimpleJwtHelper;
use App\Http\Controllers\Api\ApiController;

class SimpleJwtCheckController extends ApiController
{
    public function checkHS256()
    {
        $token = request()->bearerToken();
        $payload = SimpleJwtHelper::decodeJwtHS256($token);

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
        $payload = SimpleJwtHelper::decodeJwtRS256($token);

        if (!$payload) {
            return $this->error('Invalid token', 403);
        }

        if (time() > $payload['exp']) {
            return $this->error('Token expired', 403);
        }

        return $this->success("Token is valid",$payload);
    }

}
