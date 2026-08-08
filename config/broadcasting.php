<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 預設廣播驅動
    |--------------------------------------------------------------------------
    |
    | 此選項控制框架在需要廣播事件時所使用的預設廣播驅動。
    | 你可以將其設為下方「connections」陣列中定義的任何一組連線。
    |
    | 支援的選項："pusher"、"ably"、"redis"、"log"、"null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'null'),

    /*
    |--------------------------------------------------------------------------
    | 廣播連線設定
    |--------------------------------------------------------------------------
    |
    | 你可以在這裡定義所有用來將事件廣播至其他系統
    | 或透過 websocket 廣播的連線設定。此陣列中提供了
    | 每種可用連線類型的範例。
    |
    */

    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'host' => env('PUSHER_HOST', 'api-' . env('PUSHER_APP_CLUSTER', 'mt1') . '.pusher.com') ?: 'api-' . env('PUSHER_APP_CLUSTER', 'mt1') . '.pusher.com',
                'port' => env('PUSHER_PORT', 443),
                'scheme' => env('PUSHER_SCHEME', 'https'),
                'encrypted' => true,
                'useTLS' => env('PUSHER_SCHEME', 'https') === 'https',
            ],
            'client_options' => [
                // Guzzle 用戶端選項：https://docs.guzzlephp.org/en/stable/request-options.html
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],
    ],
];
