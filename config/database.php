<?php

use Illuminate\Support\Str;
use Pterodactyl\Helpers\Time;

return [
    /*
    |--------------------------------------------------------------------------
    | 預設資料庫連線名稱
    |--------------------------------------------------------------------------
    |
    | 你可以在這裡指定要使用下方哪一組資料庫連線
    | 作為資料庫操作的預設連線。除非在執行查詢／敘述時
    | 明確指定了其他連線，否則都會使用此連線。
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | 資料庫連線設定
    |--------------------------------------------------------------------------
    |
    | 以下是為應用程式設定的每一組資料庫連線。
    | 當然，為了簡化開發流程，下方也展示了
    | Laravel 支援的每種資料庫平台的設定範例。
    |
    |
    | Laravel 所有的資料庫操作都是透過 PHP PDO 機制完成的，
    | 所以在開始開發之前，請確認你的機器上
    | 已安裝所選資料庫對應的驅動程式。
    |
    */

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL', env('DATABASE_URL')),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'panel'),
            'username' => env('DB_USERNAME', 'pterodactyl'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => env('DB_PREFIX', ''),
            'prefix_indexes' => true,
            'strict' => env('DB_STRICT_MODE', false), // TODO：日後預設改為 true
            'engine' => null,
            'timezone' => env('DB_TIMEZONE', Time::getMySQLTimezoneOffset(env('APP_TIMEZONE', 'UTC'))),
            'sslmode' => env('DB_SSLMODE', 'prefer'),
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                PDO::MYSQL_ATTR_SSL_CERT => env('MYSQL_ATTR_SSL_CERT'),
                PDO::MYSQL_ATTR_SSL_KEY => env('MYSQL_ATTR_SSL_KEY'),
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => env('MYSQL_ATTR_SSL_VERIFY_SERVER_CERT', true),
            ]) : [],
        ],

        'mariadb' => [
            'driver' => 'mariadb',
            'url' => env('DB_URL', env('DATABASE_URL')),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'panel'),
            'username' => env('DB_USERNAME', 'pterodactyl'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => env('DB_PREFIX', ''),
            'prefix_indexes' => true,
            'strict' => env('DB_STRICT_MODE', true),
            'engine' => null,
            'timezone' => env('DB_TIMEZONE', Time::getMySQLTimezoneOffset(env('APP_TIMEZONE', 'UTC'))),
            'sslmode' => env('DB_SSLMODE', 'prefer'),
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                PDO::MYSQL_ATTR_SSL_CERT => env('MYSQL_ATTR_SSL_CERT'),
                PDO::MYSQL_ATTR_SSL_KEY => env('MYSQL_ATTR_SSL_KEY'),
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => env('MYSQL_ATTR_SSL_VERIFY_SERVER_CERT', true),
            ]) : [],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 資料庫遷移紀錄資料表
    |--------------------------------------------------------------------------
    |
    | 此資料表用來追蹤應用程式已經執行過的所有遷移（migration）。
    | 透過這項資訊，我們就能判斷磁碟上有哪些遷移
    | 尚未實際在資料庫中執行過。
    |
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis 資料庫
    |--------------------------------------------------------------------------
    |
    | Redis 是一套開放原始碼、快速且進階的鍵值儲存系統，
    | 相較於 Memcached 這類典型的鍵值系統，
    | 提供了更豐富的指令集。你可以在這裡定義你的連線設定。
    |
    */

    'redis' => [
        'client' => env('REDIS_CLIENT', 'predis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'pterodactyl'), '_') . '_database_'),
        ],

        'default' => [
            'scheme' => env('REDIS_SCHEME', 'tcp'),
            'path' => env('REDIS_PATH', '/run/redis/redis.sock'),
            'host' => env('REDIS_HOST', 'localhost'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DATABASE', 0),
            'context' => extension_loaded('redis') && env('REDIS_CLIENT') === 'phpredis' ? [
                'stream' => array_filter([
                    'verify_peer' => env('REDIS_VERIFY_PEER', true),
                    'verify_peer_name' => env('REDIS_VERIFY_PEER_NAME', true),
                    'cafile' => env('REDIS_CAFILE'),
                    'local_cert' => env('REDIS_LOCAL_CERT'),
                    'local_pk' => env('REDIS_LOCAL_PK'),
                ]),
            ] : [],
        ],

        'sessions' => [
            'scheme' => env('REDIS_SCHEME', 'tcp'),
            'path' => env('REDIS_PATH', '/run/redis/redis.sock'),
            'host' => env('REDIS_HOST', 'localhost'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DATABASE_SESSIONS', 1),
            'context' => extension_loaded('redis') && env('REDIS_CLIENT') === 'phpredis' ? [
                'stream' => array_filter([
                    'verify_peer' => env('REDIS_VERIFY_PEER', true),
                    'verify_peer_name' => env('REDIS_VERIFY_PEER_NAME', true),
                    'cafile' => env('REDIS_CAFILE'),
                    'local_cert' => env('REDIS_LOCAL_CERT'),
                    'local_pk' => env('REDIS_LOCAL_PK'),
                ]),
            ] : [],
        ],
    ],
];
