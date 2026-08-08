<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 受限環境
    |--------------------------------------------------------------------------
    |
    | 將此環境變數設為 true，可在 Panel 上啟用受限的組態設定模式。
    | 設為 true 時，儲存在資料庫中的組態設定將不會被套用。
    */

    'load_environment_only' => (bool) env('APP_ENVIRONMENT_ONLY', false),

    /*
    |--------------------------------------------------------------------------
    | 服務作者
    |--------------------------------------------------------------------------
    |
    | 每個 Panel 安裝都會被指派一組唯一的 UUID，用來識別
    | 自訂服務的作者，並透過識別 Pterodactyl 內建的標準服務
    | 讓升級作業更加容易。
    */

    'service' => [
        'author' => env('APP_SERVICE_AUTHOR', 'unknown@unknown.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | 驗證
    |--------------------------------------------------------------------------
    |
    | 登入成功或失敗事件是否應該觸發寄送電子郵件給使用者？
    */

    'auth' => [
        '2fa_required' => env('APP_2FA_REQUIRED', 0),
        '2fa' => [
            'bytes' => 32,
            'window' => env('APP_2FA_WINDOW', 4),
            'verify_newer' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 分頁
    |--------------------------------------------------------------------------
    |
    | 你可以在這裡設定特定分頁結果的數量，設定後將會全域套用生效。
    */

    'paginate' => [
        'frontend' => [
            'servers' => env('APP_PAGINATE_FRONT_SERVERS', 15),
        ],
        'admin' => [
            'servers' => env('APP_PAGINATE_ADMIN_SERVERS', 25),
            'users' => env('APP_PAGINATE_ADMIN_USERS', 25),
        ],
        'api' => [
            'nodes' => env('APP_PAGINATE_API_NODES', 25),
            'servers' => env('APP_PAGINATE_API_SERVERS', 25),
            'users' => env('APP_PAGINATE_API_USERS', 25),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Guzzle 連線
    |--------------------------------------------------------------------------
    |
    | 你可以在這裡設定 Guzzle 連線所使用的逾時時間。
    */

    'guzzle' => [
        'timeout' => env('GUZZLE_TIMEOUT', 15),
        'connect_timeout' => env('GUZZLE_CONNECT_TIMEOUT', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | CDN
    |--------------------------------------------------------------------------
    |
    | Panel 用來與 CDN 通訊、確認 Panel 是否為最新版本時所需的資訊。
    */

    'cdn' => [
        'cache_time' => 60,
        'url' => 'https://pterodactyl.tw/releases/latest.json',
    ],

    /*
    |--------------------------------------------------------------------------
    | 客戶端功能
    |--------------------------------------------------------------------------
    |
    | 允許客戶端建立自己的資料庫。
    */

    'client_features' => [
        'databases' => [
            'enabled' => env('PTERODACTYL_CLIENT_DATABASES_ENABLED', true),
            'allow_random' => env('PTERODACTYL_CLIENT_DATABASES_ALLOW_RANDOM', true),
        ],

        'schedules' => [
            // 任一排程同時能存在的任務總數上限。
            'per_schedule_task_limit' => env('PTERODACTYL_PER_SCHEDULE_TASK_LIMIT', 10),
        ],

        'allocations' => [
            'enabled' => env('PTERODACTYL_CLIENT_ALLOCATIONS_ENABLED', false),
            'range_start' => env('PTERODACTYL_CLIENT_ALLOCATIONS_RANGE_START'),
            'range_end' => env('PTERODACTYL_CLIENT_ALLOCATIONS_RANGE_END'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 檔案編輯器
    |--------------------------------------------------------------------------
    |
    | 此陣列包含可透過網頁編輯的 MIME 檔案類型。
    */

    'files' => [
        'max_edit_size' => env('PTERODACTYL_FILES_MAX_EDIT_SIZE', 1024 * 1024 * 4),
    ],

    /*
    |--------------------------------------------------------------------------
    | 動態環境變數
    |--------------------------------------------------------------------------
    |
    | 在這裡放置動態環境變數，這些變數會在伺服器建立或更新時
    | 自動附加到伺服器的環境變數欄位中。
    |
    | 項目格式應為 'key' => 'value'，其中 key 是環境變數名稱，
    | value 則是伺服器物件的欄位名稱。例如：
    |
    | 'P_SERVER_CREATED_AT' => 'created_at'
    */

    'environment_variables' => [
        'P_SERVER_ALLOCATION_LIMIT' => 'allocation_limit',
    ],

    /*
    |--------------------------------------------------------------------------
    | 資源驗證
    |--------------------------------------------------------------------------
    |
    | 此區塊控制 JS 與 CSS 資源檔案的輸出格式。
    */

    'assets' => [
        'use_hash' => env('PTERODACTYL_USE_ASSET_HASH', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | 電子郵件通知設定
    |--------------------------------------------------------------------------
    |
    | 此區塊控制要寄送哪些通知給使用者。
    */

    'email' => [
        // 伺服器完成第一次安裝流程後，是否要寄送電子郵件通知伺服器擁有者？
        'send_install_notification' => env('PTERODACTYL_SEND_INSTALL_NOTIFICATION', true),
        // 伺服器每次重新安裝時，是否要寄送電子郵件通知伺服器擁有者？
        'send_reinstall_notification' => env('PTERODACTYL_SEND_REINSTALL_NOTIFICATION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | 遙測（Telemetry）設定
    |--------------------------------------------------------------------------
    |
    | 此區塊控制 Pterodactyl 所寄送的遙測資料。
    */

    'telemetry' => [
        'enabled' => env('PTERODACTYL_TELEMETRY_ENABLED', true),
    ],

    'features' => [
        'new_server_identifiers' => (bool) env('PTERODACTYL_USE_SERVER_IDENTIFIERS', false),
    ],
];
