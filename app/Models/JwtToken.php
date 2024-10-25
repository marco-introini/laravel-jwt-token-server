<?php

namespace App\Models;

use App\Models\Scopes\ActiveJwtScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy(ActiveJwtScope::class)]
class JwtToken extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPayload(): string
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

        $base64UrlHeader = base64_encode($header);
        $base64UrlPayload = base64_encode($payload);
        $signature = hash_hmac(algo: 'sha256',
            data: $base64UrlHeader.".".$base64UrlPayload,
            key: config('jwt.secret'),
            binary: true);
        $base64UrlSignature = base64_encode($signature);
        return $base64UrlHeader.".".$base64UrlPayload.".".$base64UrlSignature;
    }

    public function checkJwtHS256(string $token): bool
    {
        return $token === $this->getJwtHS256();
    }

}
