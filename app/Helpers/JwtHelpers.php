<?php

namespace App\Helpers;

use App\Models\JwtToken;

class JwtHelpers
{

    public static function base64UrlEncode($input): string
    {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }

    public static function getJwtHS256(JwtToken $jwtToken): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = self::getPayload($jwtToken);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);
        $signature = hash_hmac(algo: 'sha256',
            data: $base64UrlHeader.".".$base64UrlPayload,
            key: config('jwt.secret'),
            binary: true);
        $base64UrlSignature = self::base64UrlEncode($signature);
        return $base64UrlHeader.".".$base64UrlPayload.".".$base64UrlSignature;
    }

    protected static function getPayload(JwtToken $jwtToken): string
    {
        $now = time();
        $jwtToken->expires_at = $now + config('jwt.ttl');
        $jwtToken->save();

        return json_encode([
            'jti' => $jwtToken->uuid,
            'sub' => $jwtToken->user->email,
            'aud' => config('jwt.aud'),
            'iss' => config('jwt.iss'),
            'iat' => $now,
            'exp' => $now + config('jwt.ttl'),
            'data' => $jwtToken->custom_claims,
        ]);
    }

}
