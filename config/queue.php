<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 預設佇列連線名稱
    |--------------------------------------------------------------------------
    |
    | Laravel 的佇列系統透過單一、統一的 API 支援多種後端，
    | 讓你能以相同的語法方便地存取每一種後端。
    | 預設的佇列連線設定於下方。
    |
    */

    'default' => env('QUEUE_CONNECTION', env('QUEUE_DRIVER', 'redis')),

    /*
    |--------------------------------------------------------------------------
    | 佇列連線設定
    |--------------------------------------------------------------------------
    |
    | 你可以在這裡設定應用程式所使用的每一種佇列後端的連線選項。
    | 這裡提供了 Laravel 所支援每種後端的範例設定，
    | 你也可以自由新增更多設定。
    |
    | 驅動："sync"、"database"、"beanstalkd"、"sqs"、"redis"、"null"
    |
    */

    'connections' => [
        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',
            'connection' => env('DB_QUEUE_CONNECTION'),
            'table' => env('DB_QUEUE_TABLE', 'jobs'),
            'queue' => env('DB_QUEUE', 'standard'),
            'retry_after' => (int) env('DB_QUEUE_RETRY_AFTER', 90),
            'after_commit' => false,
        ],

        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => env('BEANSTALKD_QUEUE_HOST', 'localhost'),
            'queue' => env('BEANSTALKD_QUEUE', 'default'),
            'retry_after' => (int) env('BEANSTALKD_QUEUE_RETRY_AFTER', 90),
            'block_for' => 0,
            'after_commit' => false,
        ],

        'sqs' => [
            'driver' => 'sqs',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            'queue' => env('SQS_QUEUE', 'default'),
            'suffix' => env('SQS_SUFFIX'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'after_commit' => false,
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_QUEUE_CONNECTION', 'default'),
            'queue' => env('REDIS_QUEUE', env('QUEUE_STANDARD', 'standard')),
            'retry_after' => (int) env('REDIS_QUEUE_RETRY_AFTER', 90),
            'block_for' => null,
            'after_commit' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 失敗的佇列工作
    |--------------------------------------------------------------------------
    |
    | 這些選項設定失敗佇列工作記錄的行為，讓你能控制
    | 失敗的工作要如何、以及儲存在哪裡。Laravel 內建支援
    | 將失敗的工作儲存在簡單的檔案或資料庫中。
    |
    | 支援的驅動："database-uuids"、"dynamodb"、"file"、"null"
    |
    */

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],
];
