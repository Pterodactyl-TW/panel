<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 具狀態（Stateful）網域
    |--------------------------------------------------------------------------
    |
    | 來自以下網域／主機的請求將會取得具狀態的 API 驗證 Cookie。
    | 通常這裡應該包含你透過前端 SPA 存取 API 時
    | 所使用的本機與正式環境網域。
    |
    */

    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        Laravel\Sanctum\Sanctum::currentApplicationUrlWithPort()
    ))),

    /*
    |--------------------------------------------------------------------------
    | Sanctum 驗證守衛（Guards）
    |--------------------------------------------------------------------------
    |
    | 此陣列包含 Sanctum 嘗試驗證請求時會檢查的驗證守衛。
    | 若這些守衛都無法驗證該請求，Sanctum 會改用
    | 請求中帶有的 bearer token 進行驗證。
    |
    */

    'guard' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | 到期時間（分鐘）
    |--------------------------------------------------------------------------
    |
    | 此值控制核發的權杖經過多少分鐘後會被視為過期。
    | 若此值為 null，個人存取權杖將永不過期。
    | 此設定不會影響第一方 session 的存續時間。
    |
    */

    'expiration' => null,

    /*
    |--------------------------------------------------------------------------
    | Sanctum 中介層（Middleware）
    |--------------------------------------------------------------------------
    |
    | 在使用 Sanctum 驗證第一方 SPA 時，你可能需要自訂
    | Sanctum 處理請求時所使用的部分中介層。
    | 你可以視需求變更下方列出的中介層。
    |
    */

    'middleware' => [
        'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
        'encrypt_cookies' => Pterodactyl\Http\Middleware\EncryptCookies::class,
        'verify_csrf_token' => Pterodactyl\Http\Middleware\VerifyCsrfToken::class,
    ],
];
