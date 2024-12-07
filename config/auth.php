<?php

return [

    'defaults' => [
        'guard' => 'web',
<<<<<<< HEAD
        'passwords' => 'usuarios',
=======
        'passwords' => 'users',
>>>>>>> 202c96f (Quinta version proyecto)
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
<<<<<<< HEAD
            'provider' => 'usuarios',
=======
            'provider' => 'users',
>>>>>>> 202c96f (Quinta version proyecto)
        ],
    ],

    'providers' => [
<<<<<<< HEAD
        'usuarios' => [
=======
        'users' => [
>>>>>>> 202c96f (Quinta version proyecto)
            'driver' => 'eloquent',
            'model' => App\Models\Usuario::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    'passwords' => [
<<<<<<< HEAD
        'usuarios' => [
            'provider' => 'usuarios',
=======
        'users' => [
            'provider' => 'users',
>>>>>>> 202c96f (Quinta version proyecto)
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
