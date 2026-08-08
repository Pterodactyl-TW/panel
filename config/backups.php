<?php

use Pterodactyl\Models\Backup;

return [
    // 此 Panel 執行個體使用的備份驅動。所有由客戶端產生的伺服器備份，
    // 預設都會儲存在這個位置。即使已經產生過備份，之後仍可以
    // 變更此設定而不會遺失資料。
    'default' => env('APP_BACKUP_DRIVER', Backup::ADAPTER_WINGS),

    // 此值用來決定 wings 上傳備份到 S3 儲存空間時所使用的
    // UploadPart 預簽章網址（presigned url）存續時間。單位為分鐘，預設為一小時。
    'presigned_url_lifespan' => (int) env('BACKUP_PRESIGNED_URL_LIFESPAN', 60),

    // 此值定義備份過程中 S3 分段上傳（multipart upload）單一分段的最大大小，
    // 必須以位元組為單位提供。預設值為 5GB。
    // 請注意，使用 AWS S3 時，單一分段的上限就是 5GB。
    'max_part_size' => env('BACKUP_MAX_PART_SIZE', 5 * 1024 * 1024 * 1024),

    // 備份在自動判定為失敗前的等待時間，單位為分鐘，預設為 6 小時。
    // 若要停用此功能，請將此值設為 `0`。
    'prune_age' => env('BACKUP_PRUNE_AGE', 360),

    // 定義使用者建立備份的節流（throttle）限制。在此預設範例中，
    // 我們允許使用者每 10 分鐘建立兩個備份（無論成功或處理中）。
    // 即使刪除了某個備份，仍會計入節流次數。
    //
    // 將 period 設為「0」可停用此節流限制。period 的單位為秒。
    'throttles' => [
        'limit' => env('BACKUP_THROTTLE_LIMIT', 2),
        'period' => env('BACKUP_THROTTLE_PERIOD', 600),
    ],

    'disks' => [
        // Wings 沒有針對本機磁碟（local disk）的設定選項，
        // 該設定是由 Daemon 的組態設定決定，而不是由 Panel 決定。
        'wings' => [
            'adapter' => Backup::ADAPTER_WINGS,
        ],

        // 將備份儲存於 Amazon S3 的相關組態設定。這裡使用的憑證
        // 與 filesystems.php 中指定的相同，但額外包含一些備份專屬的
        // 設定，特別是 bucket、location 以及 use_accelerate_endpoint。
        's3' => [
            'adapter' => Backup::ADAPTER_AWS_S3,

            'region' => env('AWS_DEFAULT_REGION'),
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),

            // 用於備份的 S3 bucket。
            'bucket' => env('AWS_BACKUPS_BUCKET'),

            // S3 bucket 中儲存備份的位置。備份會以伺服器的 UUID
            // 作為資料夾名稱進行儲存，該伺服器的每個備份
            // 都會存放在這個資料夾中。
            'prefix' => env('AWS_BACKUPS_BUCKET') ?? '',

            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'use_accelerate_endpoint' => env('AWS_BACKUPS_USE_ACCELERATE', false),

            'storage_class' => env('AWS_BACKUPS_STORAGE_CLASS'),
        ],
    ],
];
