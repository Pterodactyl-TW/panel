<?php

/*
|--------------------------------------------------------------------------
| 建立應用程式
|--------------------------------------------------------------------------
|
| 我們要做的第一件事，就是建立一個新的 Laravel 應用程式實例，
| 它扮演著 Laravel 所有元件之間的「黏著劑」角色，
| 也是綁定系統中各個部分的 IoC 容器。
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

if (isset($_ENV['APP_STORAGE_PATH'])) {
    $app->useStoragePath($_ENV['APP_STORAGE_PATH']);
}

/*
|--------------------------------------------------------------------------
| 綁定重要的介面
|--------------------------------------------------------------------------
|
| 接下來，我們需要把一些重要的介面綁定到容器中，
| 這樣我們才能在需要時解析它們。這些 kernel 負責
| 處理來自網頁與 CLI 對這個應用程式發出的請求。
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    Pterodactyl\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    Pterodactyl\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    Pterodactyl\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| 回傳應用程式
|--------------------------------------------------------------------------
|
| 這個指令碼會回傳應用程式實例。這個實例會提供給
| 呼叫端的指令碼，讓我們能把建立實例的過程，
| 與應用程式實際執行、傳送回應的過程分開處理。
|
*/

return $app;
