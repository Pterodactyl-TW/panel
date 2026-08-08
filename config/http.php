<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API 速率限制
    |--------------------------------------------------------------------------
    |
    | 定義在指定的時間區間內（預設為 1 分鐘），客戶端 API 與內部（應用程式）API
    | 各自每分鐘可執行的請求次數上限。
    |
    */
    'rate_limit' => [
        'client_period' => 1,
        'client' => env('APP_API_CLIENT_RATELIMIT', 256),

        'application_period' => 1,
        'application' => env('APP_API_APPLICATION_RATELIMIT', 256),
    ],
];
