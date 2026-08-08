<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 第三方服務
    |--------------------------------------------------------------------------
    |
    | 這個檔案用來存放第三方服務（例如 Mailgun、Postmark、AWS 等）的憑證。
    | 這是存放此類資訊的慣例位置，讓各個套件都能有一個統一的地方
    | 找到各種服務的憑證設定。
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
];
