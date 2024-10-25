<?php

namespace App\Helpers;

class JwtHelpers
{

    public static function base64UrlEncode($input): string
    {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }
}
