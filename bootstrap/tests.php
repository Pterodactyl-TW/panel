<?php

use Illuminate\Support\Str;
use NunoMaduro\Collision\Provider;
use Illuminate\Contracts\Console\Kernel;
use Symfony\Component\Console\Output\ConsoleOutput;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/app.php';

/** @var Pterodactyl\Console\Kernel $kernel */
$kernel = $app->make(Kernel::class);

/*
 * 啟動 kernel 並讓應用程式準備好進行測試。
 */
$kernel->bootstrap();

// 註冊 collision 服務提供者，讓測試設定過程中發生的錯誤
// 能以美觀的格式輸出。
(new Provider())->register();

$output = new ConsoleOutput();

$prefix = 'database.connections.' . config('database.default');
if (!Str::contains(config("$prefix.database"), 'test')) {
    $output->writeln(PHP_EOL . '<error>無法針對非測試用的資料庫執行測試流程。</error>');
    $output->writeln(PHP_EOL . '<error>目前環境指向的資料庫為：「' . config("$prefix.database") . '」。</error>');
    exit(1);
}

/*
 * 在繼續執行測試之前，先進行資料庫遷移與重新填入種子資料。
 */
if (!env('SKIP_MIGRATIONS')) {
    $output->writeln(PHP_EOL . '<info>正在為整合測試重新整理資料庫...</info>');
    $kernel->call('migrate:fresh');

    $output->writeln('<info>正在為整合測試填入種子資料...</info>' . PHP_EOL);
    $kernel->call('db:seed');
} else {
    $output->writeln(PHP_EOL . '<comment>略過資料庫遷移...</comment>' . PHP_EOL);
}
