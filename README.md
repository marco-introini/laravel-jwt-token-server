# Laravel JWT Token Server

A Laravel JWT token server.

## Description

This project demonstrates how to implement JWT (JSON Web Token) authentication in a Laravel application without relying
on external dedicated packages and with the wonderful [SimpleJWT package](https://github.com/kelvinmo/simplejwt)

## Features

- Supports HS256, RS256, and ES256 algorithms for JWT.
- Example configurations and routes for JWT integration.
- Customizable JWT settings via environment variables.

## Requirements

- PHP 8.2 or higher
- Laravel Framework 11.9 or higher
- OpenSSL extension enabled

## Installation

Clone the repository:

```bash
git clone https://github.com/marco-introini/laravel-jwt-token-server.git
cd laravel-jwt-token-server
```

Install dependencies:

```bash
composer install
```

Copy the example environment file and configure it:

```bash
cp .env.example .env
```

Update the .env file with your environment-specific settings.

Generate the application key:

```bash
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate
```

## Configuration

Environment Variables
The following environment variables are used to configure JWT:

- JWT_SECRET: The secret key used for HS256.
- JWT_TTL: The time-to-live for the token (in minutes).
- JWT_ISS: The issuer of the token.
- JWT_AUD: The audience for the token.

## JWT Algorithms

### HS256

With both SimpleJWT package and plain php: no additional setup is required.

### RS256

With both SimpleJWT package and plain php.

Generate RSA keys:

### Generate a private key

```bash
openssl genpkey -algorithm RSA -out ./storage/app/keys/rsa_private_key.pem -pkeyopt rsa_keygen_bits:2048
```

### Derive the public key from the private key

```bash
openssl rsa -pubout -in ./storage/app/keys/rsa_private_key.pem -out ./storage/app/keys/rsa_public_key.pem
```

### ES256

Available only with SimpleJWT.

Generate ECDSA keys:

#### Generate a private key

to generate the key, this is the same as P-256 in the JWA spec).

```bash
openssl ecparam -name prime256v1 -genkey -noout -out ./storage/app/keys/ecdsa_private_key.pem
```

### Derive the public key from the private key

```bash
openssl ec -in ./storage/app/keys/ecdsa_private_key.pem -pubout -out ./storage/app/keys/ecdsa_public_key.pem
```

# Usage

## Routes

Example routes are defined in routes/api.php:

```php
    Route::get('/login', LoginController::class);
    Route::get('/checkHs256', [JwtCheckController::class, 'checkHS256']);
    Route::get('/checkRs256', [JwtCheckController::class, 'checkRS256']);

    Route::prefix('simplejwt')->group(function () {
       Route::get('login', SimpleJwtLoginController::class);
        Route::get('/checkHs256', [SimpleJwtCheckController::class, 'checkHS256']);
        Route::get('/checkRs256', [SimpleJwtCheckController::class, 'checkRS256']);
        Route::get('/checkEs256', [SimpleJwtCheckController::class, 'checkES256']);
    });
```

# RSA GO Server

To demonstrate the distributed capabilities of RSA JWT Signature there is also a basic server in Go inside the `go_app`
directory which only uses the public RSA key

# Contributing

Feel free to submit issues and enhancement requests.

# License

This project is licensed under the MIT License.
