<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 預設寄件工具（Mailer）
    |--------------------------------------------------------------------------
    |
    | 此選項控制應用程式寄送電子郵件時所使用的預設寄件工具。
    | 你可以視需求設定並使用其他替代的寄件工具；
    | 不過預設情況下會使用這裡指定的寄件工具。
    |
    */

    'default' => env('MAIL_MAILER', env('MAIL_DRIVER', 'smtp')),

    /*
    |--------------------------------------------------------------------------
    | 寄件工具組態設定
    |--------------------------------------------------------------------------
    |
    | 你可以在這裡設定應用程式所使用的所有寄件工具及其各自的設定。
    | 這裡已經幫你設定好幾個範例，你也可以視應用程式需求
    | 自由新增自己的設定。
    |
    | Laravel 在寄送電子郵件時支援多種「傳輸方式（transport）」驅動。
    | 你可以在下方為每個寄件工具指定要使用哪一種。
    | 你可以視需求自由新增額外的寄件工具。
    |
    | 支援的選項："smtp"、"sendmail"、"mailgun"、"ses"、"ses-v2"、
    |             "postmark"、"log"、"array"、"failover"
    |
    */

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', env('SERVER_NAME')),
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'mailgun' => [
            'transport' => 'mailgun',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 全域「寄件者」地址
    |--------------------------------------------------------------------------
    |
    | 你可能會希望應用程式寄出的所有電子郵件都使用同一個地址寄送。
    | 你可以在這裡指定應用程式寄出所有電子郵件時
    | 全域使用的名稱與地址。
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', env('MAIL_FROM', 'hello@example.com')),
        'name' => env('MAIL_FROM_NAME', 'Pterodactyl Panel'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown 郵件設定
    |--------------------------------------------------------------------------
    |
    | 若你使用以 Markdown 為基礎的郵件轉譯方式，可以在這裡設定
    | 佈景主題與元件路徑，讓你能自訂電子郵件的樣式設計。
    | 當然，你也可以直接沿用 Laravel 的預設設定！
    |
    */

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],
];
