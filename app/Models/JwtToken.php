<?php

namespace App\Models;

use App\Helpers\JwtHelpers;
use App\Models\Scopes\ActiveJwtScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy(ActiveJwtScope::class)]
class JwtToken extends Model
{
    use HasFactory, HasUuids;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function getPayload(): string
    {
        $now = time();
        $this->expires_at = $now + config('jwt.ttl');
        $this->save();

        return json_encode([
            'jti' => $this->uuid,
            'sub' => $this->user->email,
            'aud' => config('jwt.aud'),
            'iss' => config('jwt.iss'),
            'iat' => $now,
            'exp' => $now + config('jwt.ttl'),
            'data' => $this->custom_claims,
        ]);
    }

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'custom_claims' => 'array'
        ];
    }

    public function getJwtHS256(): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = $this->getPayload();

        $base64UrlHeader = JwtHelpers::base64UrlEncode($header);
        $base64UrlPayload = JwtHelpers::base64UrlEncode($payload);
        $signature = hash_hmac(algo: 'sha256',
            data: $base64UrlHeader.".".$base64UrlPayload,
            key: config('jwt.secret'),
            binary: true);
        $base64UrlSignature = JwtHelpers::base64UrlEncode($signature);
        return $base64UrlHeader.".".$base64UrlPayload.".".$base64UrlSignature;
    }

    public function checkJwtHS256(string $token): bool
    {
        return $token === $this->getJwtHS256();
    }

}
