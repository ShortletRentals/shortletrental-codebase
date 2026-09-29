<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => '483172762890-guisaoa65mg80h49u4hcdbmnole5p4ph.apps.googleusercontent.com',
        'client_secret' => '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',
        // 'client_id' => env('GOOGLE_CLIENT_ID'),
        // 'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        // 'redirect' => url('/auth/google/callback'),
        'redirect' => 'https://shortletrental.com/auth/google/callback',
    ],

    'facebook' => [
        'client_id' => '510654144535597',
        'client_secret' => '9a31e8ffbaf6a243ff6f778c16fa0a78',
        // 'redirect' => url('/auth/facebook/callback'),
        'redirect' => 'https://shortletrental.com/auth/facebook/callback',
        // 'redirect' => 'http://localhost/shortletrental/auth/facebook/callback',
    ],
];
