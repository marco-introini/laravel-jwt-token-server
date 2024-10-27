<?php

namespace App\Helpers;

use App\Models\JwtToken;
use Illuminate\Support\Facades\Storage;

class JwtHelpers
{

    public static function base64UrlEncode($input): string
    {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }

    public static function createJwtHS256(JwtToken $jwtToken): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = self::getPayload($jwtToken);

        $base64Header = self::base64UrlEncode($header);
        $base64Payload = self::base64UrlEncode($payload);
        $signature = hash_hmac(algo: 'sha256',
            data: $base64Header.".".$base64Payload,
            key: config('jwt.secret'),
            binary: true);
        $base64Signature = self::base64UrlEncode($signature);
        return $base64Header.".".$base64Payload.".".$base64Signature;
    }

    public static function decodeJwtHS256(string $jwtToken): array|false
    {
        $tokenParts = explode('.', $jwtToken);
        if (count($tokenParts) !== 3) {
            return false;
        }
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature = base64_decode($tokenParts[2]);
        $expectedSignature = hash_hmac(algo: 'sha256',
            data: $tokenParts[0].".".$tokenParts[1],
            key: config('jwt.secret'),
            binary: true);
        if ($signature !== $expectedSignature) {
            return false;
        }
        return json_decode($payload, true);
    }

    protected static function getPayload(JwtToken $jwtToken): string
    {
        $now = time();
        $jwtToken->expires_at = $now + config('jwt.ttl');
        $jwtToken->save();

        return json_encode([
            'jti' => $jwtToken->id,
            'sub' => $jwtToken->user->email,
            'aud' => config('jwt.aud'),
            'iss' => config('jwt.iss'),
            'iat' => $now,
            'exp' => $now + config('jwt.ttl'),
            'data' => $jwtToken->custom_claims,
        ]);
    }

    public static function createJwtRS256(JwtToken $jwtToken): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'RS256']);
        $payload = self::getPayload($jwtToken);

        $base64Header = self::base64UrlEncode($header);
        $base64Payload = self::base64UrlEncode($payload);
        $signatureBase = "$base64Header.$base64Payload";

        $privateKey = Storage::disk('keys')->get('rsa_private_key.pem');
        openssl_sign($signatureBase, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $base64Signature = self::base64UrlEncode($signature);
        return $base64Header.".".$base64Payload.".".$base64Signature;
    }

    public static function createJwtES256(JwtToken $jwtToken): string
    {
        return "TODO";
    }


}
