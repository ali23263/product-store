<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:3000'),
        'http://localhost:3000',
        'http://localhost:5173', // Vite default port
        'http://localhost:8080',
        'http://frontend:3000',
        'http://frontend:5173',
        'http://host.docker.internal:3000',
        'http://host.docker.internal:5173',
    ],

    'allowed_origins_patterns' => [
        '/^http:\/\/localhost:\d+$/',
        '/^http:\/\/127\.0\.0\.1:\d+$/',
        '/^http:\/\/frontend:\d+$/',
        '/^http:\/\/host\.docker\.internal:\d+$/',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 3600, // Cache preflight requests for 1 hour

    'supports_credentials' => true,

];
