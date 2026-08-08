<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 預設檔案系統磁碟
    |--------------------------------------------------------------------------
    |
    | 你可以在這裡指定框架要使用的預設檔案系統磁碟。
    | 應用程式可以使用「local」磁碟，以及多種
    | 雲端儲存磁碟來進行檔案儲存。
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | 檔案系統磁碟設定
    |--------------------------------------------------------------------------
    |
    | 你可以在下方視需求設定任意數量的檔案系統磁碟，
    | 甚至可以為同一個驅動設定多組磁碟。
    | 這裡提供了大部分支援的儲存驅動的設定範例供你參考。
    |
    | 支援的驅動："local"、"ftp"、"sftp"、"s3"
    |
    */

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 符號連結（Symbolic Links）
    |--------------------------------------------------------------------------
    |
    | 你可以在這裡設定執行 `storage:link` Artisan 指令時會建立的符號連結。
    | 陣列的 key 應該是連結的位置，value 則應該是連結指向的目標。
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
