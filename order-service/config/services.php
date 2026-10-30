
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk layanan eksternal dan microservice.
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

    'product' => [
        'base_url' => env(
            'PRODUCT_SERVICE_URL',
            'http://product-service:3000'
        ),
        'timeout' => 5,
    ],

    'cart' => [
        'base_url' => env(
            'CART_SERVICE_URL',
            'http://cart-service:3001'
        ),
        'timeout' => 5,
    ],

];
