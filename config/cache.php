<?php

use Illuminate\Support\Str;

return [
    /*
    |--------------------------------------------------------------------------
    | 預設快取儲存區
    |--------------------------------------------------------------------------
    |
    | 此選項控制框架所使用的預設快取儲存區。
    | 當應用程式內執行快取操作時未明確指定其他儲存區，
    | 就會使用此連線。
    |
    */

    'default' => env('CACHE_STORE', env('CACHE_DRIVER', 'redis')),

    /*
    |--------------------------------------------------------------------------
    | 快取儲存區設定
    |--------------------------------------------------------------------------
    |
    | 你可以在這裡定義應用程式所有的快取「儲存區」及其驅動。
    | 你甚至可以為同一種快取驅動定義多個儲存區，
    | 用來分組儲存快取中的不同類型項目。
    |
    | 支援的驅動："array"、"database"、"file"、"memcached"、
    |             "redis"、"octane"、"null"
    |
    */

    'stores' => [
        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'connection' => env('DB_CACHE_CONNECTION'),
            'table' => env('DB_CACHE_TABLE', 'cache'),
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'),
            'lock_table' => env('DB_CACHE_LOCK_TABLE'),
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_CACHE_CONNECTION', 'default'),
            'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'),
        ],

        'sessions' => [
            'driver' => env('SESSION_DRIVER', 'database'),
            'table' => 'sessions',
            'connection' => env('SESSION_DRIVER') === 'redis' ? 'sessions' : null,
        ],

        'octane' => [
            'driver' => 'octane',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 快取金鑰前綴
    |--------------------------------------------------------------------------
    |
    | 當使用 APC、資料庫、memcached、Redis 或 DynamoDB 快取儲存區時，
    | 可能會有其他應用程式使用相同的快取。因此，
    | 你可以為每一個快取金鑰加上前綴以避免衝突。
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'pterodactyl'), '_') . '_cache_'),
];
