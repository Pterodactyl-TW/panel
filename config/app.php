<?php


return [
    /*
    |--------------------------------------------------------------------------
    | 應用程式版本
    |--------------------------------------------------------------------------
    | 此值是在建立 Pterodactyl 發行版本時設定的。
    | 若你並非在維護自己內部的版本，不應該變更此值。
    */

    'version' => 'canary',

    /*
    |--------------------------------------------------------------------------
    | 應用程式名稱
    |--------------------------------------------------------------------------
    |
    | 此值是你應用程式的名稱，當框架需要在通知
    | 或其他需要顯示應用程式名稱的 UI 元件中
    | 放上應用程式名稱時，就會使用這個值。
    |
    */

    'name' => env('APP_NAME', 'Pterodactyl'),

    /*
    |--------------------------------------------------------------------------
    | 應用程式環境
    |--------------------------------------------------------------------------
    |
    | 此值決定你的應用程式目前執行所在的「環境」。
    | 這可能會影響你偏好如何設定應用程式使用的
    | 各種服務。請在你的「.env」檔案中設定此值。
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | 應用程式除錯模式
    |--------------------------------------------------------------------------
    |
    | 當應用程式處於除錯模式時，應用程式內發生的每個錯誤
    | 都會顯示包含堆疊追蹤（stack trace）的詳細錯誤訊息。
    | 若停用此模式，則只會顯示簡單的通用錯誤頁面。
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | 應用程式網址
    |--------------------------------------------------------------------------
    |
    | 使用 Artisan 命令列工具時，主控台會用此網址
    | 正確產生所需的網址。你應該將此值設為
    | 應用程式的根路徑，以便在 Artisan 指令中使用。
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | 應用程式時區
    |--------------------------------------------------------------------------
    |
    | 你可以在這裡指定應用程式的預設時區，
    | PHP 的日期與日期時間函式將會使用此設定。
    | 時區預設為「UTC」，適合大部分的使用情境。
    |
    */

    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | 應用程式語系組態設定
    |--------------------------------------------------------------------------
    |
    | 應用程式語系決定了 Laravel 翻譯／在地化方法
    | 所使用的預設語系。此選項可以設為
    | 你規劃要提供翻譯字串的任何語系。
    |
    */

    'locale' => env('APP_LOCALE', 'zh_TW'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | 加密金鑰
    |--------------------------------------------------------------------------
    |
    | 此金鑰由 Laravel 的加密服務使用，應設定為
    | 一組隨機的 32 字元字串，以確保所有加密後的值都安全無虞。
    | 你應該在部署應用程式之前完成此設定。
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | 維護模式驅動
    |--------------------------------------------------------------------------
    |
    | 這些組態設定選項決定了用來判斷與管理 Laravel
    | 「維護模式」狀態所使用的驅動。「cache」驅動
    | 能讓維護模式在多台機器之間統一控制。
    |
    | 支援的驅動："file"、"cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | 例外回報器組態設定
    |--------------------------------------------------------------------------
    |
    | 若你發現 Panel 出現奇怪的行為，卻沒有任何例外被記錄下來，
    | 可以嘗試將下方的環境變數改為 true。
    | 這會覆蓋 Panel 預設「不回報」的行為，改為記錄
    | 所有的例外。這麼做會產生大量的日誌紀錄。
    |
    */

    'exceptions' => [
        'report_all' => env('APP_REPORT_ALL_EXCEPTIONS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | 自動載入的服務提供者
    |--------------------------------------------------------------------------
    |
    | 這裡列出的服務提供者會在應用程式收到請求時自動載入。
    | 你可以自由地將自己的服務加入這個陣列，
    | 為應用程式擴充額外的功能。
    |
    */

    'providers' => [
        /*
         * Laravel 框架服務提供者...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * 應用程式服務提供者...
         */
        Pterodactyl\Providers\ActivityLogServiceProvider::class,
        Pterodactyl\Providers\AppServiceProvider::class,
        Pterodactyl\Providers\AuthServiceProvider::class,
        Pterodactyl\Providers\BackupsServiceProvider::class,
        Pterodactyl\Providers\BladeServiceProvider::class,
        Pterodactyl\Providers\EventServiceProvider::class,
        Pterodactyl\Providers\HashidsServiceProvider::class,
        Pterodactyl\Providers\RouteServiceProvider::class,
        Pterodactyl\Providers\RepositoryServiceProvider::class,
        Pterodactyl\Providers\ViewComposerServiceProvider::class,

        /*
         * 額外的相依套件
         */
        Prologue\Alerts\AlertsServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | 類別別名（Class Aliases）
    |--------------------------------------------------------------------------
    |
    | 此陣列中的類別別名會在應用程式啟動時被註冊。
    | 不過你可以自由註冊任意數量的別名，
    | 因為這些別名是「延遲（lazy）」載入的，不會影響效能。
    |
    */

    'aliases' => Illuminate\Support\Facades\Facade::defaultAliases()->merge([
        'Alert' => Prologue\Alerts\Facades\Alert::class,
        'Carbon' => Carbon\Carbon::class,
        'JavaScript' => Laracasts\Utilities\JavaScript\JavaScriptFacade::class,
        'Theme' => Pterodactyl\Extensions\Facades\Theme::class,

        // 自訂 Facade
        'Activity' => Pterodactyl\Facades\Activity::class,
        'LogBatch' => Pterodactyl\Facades\LogBatch::class,
        'LogTarget' => Pterodactyl\Facades\LogTarget::class,
    ])->toArray(),
];
