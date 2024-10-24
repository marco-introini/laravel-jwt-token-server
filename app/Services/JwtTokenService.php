<?php

namespace App\Services;

use App\Models\JwtToken;
use App\Models\User;

class JwtTokenService
{
    protected $secretKey = 'secret';
    protected $ttl = 60 * 60 * 24;
    protected $algorithm = 'HS256';
    protected $iss = 'http://localhost:8000';
    protected $aud = 'http://localhost:8000';
    protected $iat;


    public function generateToken(User $user): JwtToken
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => $this->algorithm]);
        $payload = json_encode([
            'sub' => '1234567890',
            'name' => $subject,
            'iat' => 1516239022,
            'iss' => 'garyclarke.tech'
        ]);

        $base64UrlHeader = base64_encode($header);
        $base64UrlPayload = base64_encode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader.".".$base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = base64_encode($signature);
        return $base64UrlHeader.".".$base64UrlPayload.".".$base64UrlSignature;
    }

}
