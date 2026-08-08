<?php

namespace Pterodactyl\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\VarDumper\VarDumper;
use Pterodactyl\Services\Telemetry\TelemetryCollectionService;

class TelemetryCommand extends Command
{
    protected $description = '顯示若啟用遙測資料蒐集功能時，將會傳送給 Pterodactyl 遙測服務的所有資料。';

    protected $signature = 'p:telemetry';

    /**
     * TelemetryCommand constructor.
     */
    public function __construct(private TelemetryCollectionService $telemetryCollectionService)
    {
        parent::__construct();
    }

    /**
     * Handle execution of command.
     *
     * @throws \Pterodactyl\Exceptions\Model\DataValidationException
     */
    public function handle()
    {
        $this->output->info('正在蒐集遙測資料，這可能需要一些時間...');

        VarDumper::dump($this->telemetryCollectionService->collect());
    }
}
