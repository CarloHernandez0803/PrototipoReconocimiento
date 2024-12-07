<?php

use Illuminate\Support\Str;

return [

<<<<<<< HEAD
    'driver' => 'array',

    'lifetime' => 20,
=======
    'driver' => env('SESSION_DRIVER', 'database'),

    'lifetime' => env('SESSION_LIFETIME', 20),
>>>>>>> 202c96f (Quinta version proyecto)

    'expire_on_close' => false,

    'encrypt' => false,

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION'),

<<<<<<< HEAD
    'table' => 'sesiones',
    'primary_key' => 'id_sesion',
=======
    'table' => 'sessions',
>>>>>>> 202c96f (Quinta version proyecto)

    'store' => env('SESSION_STORE'),

    'lottery' => [2, 100],

<<<<<<< HEAD
    'cookie' => env('SESSION_COOKIE', Str::slug(env('APP_NAME', 'laravel'), '_').'_session'),
=======
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),
>>>>>>> 202c96f (Quinta version proyecto)

    'path' => '/',

    'domain' => env('SESSION_DOMAIN'),

    'secure' => env('SESSION_SECURE_COOKIE'),

    'http_only' => true,

    'same_site' => 'lax',

    'partitioned' => false,
];
