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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    'proxypay' => [
        'base_url' => env('PROXYPAY_BASE_URL', 'https://api.sandbox.proxypay.co.ao'),
        'api_key' => env('PROXYPAY_API_KEY', ''),
        'pos_id' => env('PROXYPAY_POS_ID', 0),
    ],

    'multicaixa_express' => [
        'base_url' => env('MULTICAIXA_EXPRESS_BASE_URL', 'https://sandbox.paybyrd.com'),
        'api_key' => env('MULTICAIXA_EXPRESS_API_KEY', ''),
    ],

    'bitpay' => [
        'base_url' => env('BITPAY_BASE_URL', 'https://api-sandbox.bitpay.ao'),
        'secret_key' => env('BITPAY_SECRET_KEY', ''),
        'public_key' => env('BITPAY_PUBLIC_KEY', ''),
        'webhook_url' => env('BITPAY_WEBHOOK_URL', ''),
    ],

];
