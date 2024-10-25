<?php

return [
    'secret' => env('JWT_SECRET', '<KEY>'),
    'ttl' => env('JWT_TTL', 60),
    'iss' => env('JWT_ISS', 'http://localhost'),
    'aud' => env('JWT_AUD', 'http://localhost'),
];
