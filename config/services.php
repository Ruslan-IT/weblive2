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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'robokassa' => [
        'merchant_login' => env('ROBOKASSA_MERCHANT_LOGIN'),
        'password_1' => env('ROBOKASSA_PASS_1'),
        'password_2' => env('ROBOKASSA_PASS_2'),
        'test_mode' => env('ROBOKASSA_TEST_MODE', true),
        'password_1_test' => env('ROBOKASSA_PASS_1_TEST'),
        'password_2_test' => env('ROBOKASSA_PASS_2_TEST'),
    ],

];
