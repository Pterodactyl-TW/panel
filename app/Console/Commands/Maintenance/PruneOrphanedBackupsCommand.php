<?php

namespace Pterodactyl\Console\Commands\Maintenance;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Pterodactyl\Repositories\Eloquent\BackupRepository;

class PruneOrphanedBackupsCommand extends Command
{
    protected $signature = 'p:maintenance:prune-backups {--prune-age=}';

    protected $description = '將所有超過「n」分鐘、尚未完成的備份標記為失敗。';

    /**
     * PruneOrphanedBackupsCommand constructor.
     */
    public function __construct(private BackupRepository $backupRepository)
    {
        parent::__construct();
    }

    public function handle()
    {
        $since = $this->option('prune-age') ?? config('backups.prune_age', 360);
        if (!$since || !is_digit($since)) {
            throw new \InvalidArgumentException('The "--prune-age" argument must be a value greater than 0.');
        }

        $query = $this->backupRepository->getBuilder()
            ->whereNull('completed_at')
            ->where('created_at', '<=', CarbonImmutable::now()->subMinutes($since)->toDateTimeString());

        $count = $query->count();
        if (!$count) {
            $this->info('沒有需要標記為失敗的孤立備份。');

            return;
        }

        $this->warn("正在將 $count 筆超過 $since 分鐘、尚未完成的備份標記為失敗。");

        $query->update([
            'is_successful' => false,
            'completed_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }
}
