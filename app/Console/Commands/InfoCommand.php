<?php

namespace Pterodactyl\Console\Commands;

use Illuminate\Console\Command;
use Pterodactyl\Services\Helpers\SoftwareVersionService;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class InfoCommand extends Command
{
    protected $description = '顯示應用程式、資料庫與電子郵件的組態設定，以及 Panel 版本。';

    protected $signature = 'p:info';

    /**
     * VersionCommand constructor.
     */
    public function __construct(private ConfigRepository $config, private SoftwareVersionService $versionService)
    {
        parent::__construct();
    }

    /**
     * Handle execution of command.
     */
    public function handle()
    {
        $this->output->title('版本資訊');
        $this->table([], [
            ['Panel 版本', $this->config->get('app.version')],
            ['最新版本', $this->versionService->getPanel()],
            ['是否為最新', $this->versionService->isLatestPanel() ? '是' : $this->formatText('否', 'bg=red')],
            ['唯一識別碼', $this->config->get('pterodactyl.service.author')],
        ], 'compact');

        $this->output->title('應用程式組態設定');
        $this->table([], [
            ['環境', $this->formatText($this->config->get('app.env'), $this->config->get('app.env') === 'production' ?: 'bg=red')],
            ['除錯模式', $this->formatText($this->config->get('app.debug') ? '是' : '否', !$this->config->get('app.debug') ?: 'bg=red')],
            ['安裝網址', $this->config->get('app.url')],
            ['安裝目錄', base_path()],
            ['時區', $this->config->get('app.timezone')],
            ['快取驅動程式', $this->config->get('cache.default')],
            ['佇列驅動程式', $this->config->get('queue.default')],
            ['Session 驅動程式', $this->config->get('session.driver')],
            ['檔案系統驅動程式', $this->config->get('filesystems.default')],
            ['預設佈景主題', $this->config->get('themes.active')],
            ['信任代理伺服器', $this->config->get('trustedproxies.proxies')],
        ], 'compact');

        $this->output->title('資料庫組態設定');
        $driver = $this->config->get('database.default');
        $this->table([], [
            ['驅動程式', $driver],
            ['主機', $this->config->get("database.connections.$driver.host")],
            ['連接埠', $this->config->get("database.connections.$driver.port")],
            ['資料庫', $this->config->get("database.connections.$driver.database")],
            ['使用者名稱', $this->config->get("database.connections.$driver.username")],
        ], 'compact');

        // TODO: Update this to handle other mail drivers
        $this->output->title('電子郵件組態設定');
        $this->table([], [
            ['驅動程式', $this->config->get('mail.default')],
            ['主機', $this->config->get('mail.mailers.smtp.host')],
            ['連接埠', $this->config->get('mail.mailers.smtp.port')],
            ['使用者名稱', $this->config->get('mail.mailers.smtp.username')],
            ['寄件者地址', $this->config->get('mail.from.address')],
            ['寄件者名稱', $this->config->get('mail.from.name')],
            ['加密方式', $this->config->get('mail.mailers.smtp.encryption')],
        ], 'compact');
    }

    /**
     * Format output in a Name: Value manner.
     */
    private function formatText(string $value, string $opts = ''): string
    {
        return sprintf('<%s>%s</>', $opts, $value);
    }
}
