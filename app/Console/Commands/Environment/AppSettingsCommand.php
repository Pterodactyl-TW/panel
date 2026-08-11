<?php

namespace Pterodactyl\Console\Commands\Environment;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Pterodactyl\Traits\Commands\EnvironmentWriterTrait;

class AppSettingsCommand extends Command
{
    use EnvironmentWriterTrait;

    public const CACHE_DRIVERS = [
        'redis' => 'Redis（建議）',
        'memcached' => 'Memcached',
        'file' => '本地檔案',
    ];

    public const SESSION_DRIVERS = [
        'redis' => 'Redis（建議）',
        'memcached' => 'Memcached',
        'database' => 'MySQL 資料庫',
        'file' => '本地檔案',
        'cookie' => 'Cookie',
    ];

    public const QUEUE_DRIVERS = [
        'redis' => 'Redis（建議）',
        'database' => 'MySQL 資料庫',
        'sync' => 'Sync（同步）',
    ];

    protected $description = '配置 Panel 的基本環境設定。';

    protected $signature = 'p:environment:setup
                            {--new-salt : 是否要為 Hashids 產生新的 salt。}
                            {--author= : 此實例建立的服務要關聯的電子郵件地址。}
                            {--url= : 此 Panel 執行所在的網址。}
                            {--timezone= : Panel 時間所使用的時區。}
                            {--cache= : 要使用的快取驅動程式後端。}
                            {--session= : 要使用的 Session 驅動程式後端。}
                            {--queue= : 要使用的佇列驅動程式後端。}
                            {--redis-host= : 用於連線的 Redis 主機。}
                            {--redis-pass= : 連接 Redis 使用的密碼。}
                            {--redis-port= : 連接 Redis 使用的連接埠。}
                            {--settings-ui= : 啟用或停用設定介面。}
                            {--telemetry= : 啟用或停用匿名遙測。}';

    protected array $variables = [];

    /**
     * AppSettingsCommand constructor.
     */
    public function __construct(private Kernel $console)
    {
        parent::__construct();
    }

    /**
     * Handle command execution.
     *
     * @throws \Pterodactyl\Exceptions\PterodactylException
     */
    public function handle(): int
    {
        if (empty(config('hashids.salt')) || $this->option('new-salt')) {
            $this->variables['HASHIDS_SALT'] = str_random(20);
        }

        $this->output->comment('請提供此 Panel 匯出的 Egg 檔時應歸屬的電子郵件地址，此欄位須為有效的電子郵件地址。');
        $this->variables['APP_SERVICE_AUTHOR'] = $this->option('author') ?? $this->ask(
            'Egg 作者電子郵件',
            config('pterodactyl.service.author', 'unknown@unknown.com')
        );

        if (!filter_var($this->variables['APP_SERVICE_AUTHOR'], FILTER_VALIDATE_EMAIL)) {
            $this->output->error('提供的服務作者電子郵件無效。');

            return 1;
        }

        $this->output->comment('應用程式網址必須以 https:// 或 http:// 開頭（視你是否使用 SSL 而定）。若未包含通訊協定開頭，你的電子郵件與其他內容將會連結到錯誤的位置。');
        $this->variables['APP_URL'] = $this->option('url') ?? $this->ask(
            '應用程式網址',
            config('app.url', 'http://panel.example.com')
        );

        $this->output->comment('時區應符合 PHP 支援的時區之一。若你不清楚時區，請參考官方文件 https://php.net/manual/en/timezones.php');
        $this->variables['APP_TIMEZONE'] = $this->option('timezone') ?? $this->anticipate(
            '應用程式時區',
            \DateTimeZone::listIdentifiers(),
            config('app.timezone')
        );

        $selected = config('cache.default', 'redis');
        $this->variables['CACHE_DRIVER'] = $this->option('cache') ?? $this->choice(
            '快取驅動程式',
            self::CACHE_DRIVERS,
            array_key_exists($selected, self::CACHE_DRIVERS) ? $selected : null
        );

        $selected = config('session.driver', 'redis');
        $this->variables['SESSION_DRIVER'] = $this->option('session') ?? $this->choice(
            'Session 驅動程式',
            self::SESSION_DRIVERS,
            array_key_exists($selected, self::SESSION_DRIVERS) ? $selected : null
        );

        $selected = config('queue.default', 'redis');
        $this->variables['QUEUE_CONNECTION'] = $this->option('queue') ?? $this->choice(
            '佇列驅動程式',
            self::QUEUE_DRIVERS,
            array_key_exists($selected, self::QUEUE_DRIVERS) ? $selected : null
        );

        if (!is_null($this->option('settings-ui'))) {
            $this->variables['APP_ENVIRONMENT_ONLY'] = $this->option('settings-ui') == 'true' ? 'false' : 'true';
        } else {
            $this->variables['APP_ENVIRONMENT_ONLY'] = $this->confirm('啟用介面式設定編輯器？', true) ? 'false' : 'true';
        }

        $this->output->comment('關於遙測資料蒐集的詳細資訊，請參考 https://pterodactyl.tw/panel/1.0/additional_configuration.html#telemetry。');
        $this->variables['PTERODACTYL_TELEMETRY_ENABLED'] = $this->option('telemetry') ?? $this->confirm(
            '是否啟用傳送匿名遙測資料？',
            config('pterodactyl.telemetry.enabled', true)
        ) ? 'true' : 'false';

        // Make sure session cookies are set as "secure" when using HTTPS
        if (str_starts_with($this->variables['APP_URL'], 'https://')) {
            $this->variables['SESSION_SECURE_COOKIE'] = 'true';
        }

        $this->checkForRedis();
        $this->writeToEnvironment($this->variables);

        $this->info($this->console->output());

        return 0;
    }

    /**
     * Check if redis is selected, if so, request connection details and verify them.
     */
    private function checkForRedis()
    {
        $items = collect($this->variables)->filter(function ($item) {
            return $item === 'redis';
        });

        // Redis was not selected, no need to continue.
        if (count($items) === 0) {
            return;
        }

        $this->output->note('你已為一個或多個選項選擇了 Redis 驅動程式，請在下方提供有效的連線資訊。多數情況下，除非你修改過設定，否則可直接使用預設值。');
        $this->variables['REDIS_HOST'] = $this->option('redis-host') ?? $this->ask(
            'Redis 主機',
            config('database.redis.default.host')
        );

        $askForRedisPassword = true;
        if (!empty(config('database.redis.default.password'))) {
            $this->variables['REDIS_PASSWORD'] = config('database.redis.default.password');
            $askForRedisPassword = $this->confirm('看起來 Redis 已經設定了密碼，你想要變更它嗎？');
        }

        if ($askForRedisPassword) {
            $this->output->comment('預設情況下，Redis 伺服器並沒有密碼，因為它是在本機執行，外部無法存取。若你的情況正是如此，直接按下 Enter 即可。');
            $this->variables['REDIS_PASSWORD'] = $this->option('redis-pass') ?? $this->output->askHidden(
                'Redis 密碼'
            );
        }

        if (empty($this->variables['REDIS_PASSWORD'])) {
            $this->variables['REDIS_PASSWORD'] = 'null';
        }

        $this->variables['REDIS_PORT'] = $this->option('redis-port') ?? $this->ask(
            'Redis 連接埠',
            config('database.redis.default.port')
        );
    }
}
