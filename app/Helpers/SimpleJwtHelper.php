<?php

namespace App\Helpers;

use App\Models\JwtToken;
use Illuminate\Support\Facades\Storage;
use SimpleJWT\InvalidTokenException;
use SimpleJWT\JWT;
use SimpleJWT\Keys\KeySet;

class SimpleJwtHelper
{
    public static function createJwtHS256(JwtToken $jwtToken): string
    {
        $set = KeySet::createFromSecret(config('jwt.secret'));

        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $payload = self::getPayload($jwtToken);
        $jwt = new JWT($header, $payload);

        return $jwt->encode($set);
    }

    public static function decodeJwtHS256(string $jwtToken): array|false
    {
        $set = KeySet::createFromSecret(config('jwt.secret'));
        try {
            $jwt = JWT::decode($jwtToken, $set, 'HS256');
        } catch (InvalidTokenException $e) {
            return false;
        }
        return json_decode($jwt->getClaims(), true);
    }

    protected static function getPayload(JwtToken $jwtToken): array
    {
        $now = time();
        $jwtToken->expires_at = $now + config('jwt.ttl');
        $jwtToken->save();

        return [
            'jti' => $jwtToken->id,
            'sub' => $jwtToken->user->email,
            'aud' => config('jwt.aud'),
            'iss' => config('jwt.iss'),
            'iat' => $now,
            'exp' => $now + config('jwt.ttl'),
            'data' => $jwtToken->custom_claims,
        ];
    }

    public static function createJwtRS256(JwtToken $jwtToken): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'RS256']);
        $payload = self::getPayload($jwtToken);



        return "";
    }

    public static function decodeJwtRS256(string $jwtToken): array|false
    {

        return json_decode("", true);
    }
}
