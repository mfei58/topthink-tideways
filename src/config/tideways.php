<?php

return array(
    'enable' => env('tideways.enable', false),
    'connection' => [
        'mongodb' => [
            'host' => env('tideways.mongodb_host', 'mongodb://127.0.0.1:27017'),
            'db' => env('tideways.mongodb_db', 'xhprof'),
            'options' => [
                'username' => env("tideways.mongodb_username") ?: null,
                'password' => env("tideways.mongodb_password") ?: null,
            ]
        ]
    ]
);