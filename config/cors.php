<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 跨來源資源共用（CORS）組態設定
    |--------------------------------------------------------------------------
    |
    | 你可以在這裡設定跨來源資源共用（「CORS」）的相關選項，
    | 這會決定瀏覽器中允許執行哪些跨來源操作。
    | 你可以依需求自由調整這些設定。
    |
    | 想了解更多：https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    /*
     * 你可以為 1 個或多個路徑啟用 CORS。
     * 範例：['api/*']
     */
    'paths' => ['/api/client', '/api/application', '/api/client/*', '/api/application/*'],

    /*
     * 比對請求方法（method）。`['*']` 代表允許所有方法。
     */
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD'],

    /*
     * 比對請求來源（origin）。`['*']` 代表允許所有來源。也可以使用萬用字元，例如 `*.mydomain.com`
     */
    'allowed_origins' => explode(',', env('APP_CORS_ALLOWED_ORIGINS') ?? ''),

    /*
     * 可搭配 `preg_match` 使用、用來比對來源的樣式。
     */
    'allowed_origins_patterns' => [],

    /*
     * 設定 Access-Control-Allow-Headers 回應標頭。`['*']` 代表允許所有標頭。
     */
    'allowed_headers' => ['*'],

    /*
     * 使用這些標頭設定 Access-Control-Expose-Headers 回應標頭。
     */
    'exposed_headers' => [],

    /*
     * 當值大於 0 時，設定 Access-Control-Max-Age 回應標頭。
     */
    'max_age' => 0,

    /*
     * 設定 Access-Control-Allow-Credentials 標頭。
     */
    'supports_credentials' => true,
];
