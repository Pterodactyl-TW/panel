<?php

namespace Pterodactyl\Console\Commands\Environment\Addons;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class RunHooksCommand extends Command
{
    protected $signature = 'p:environment:addons:run-hooks
                            {event : 要執行掛勾腳本的生命週期事件（例如 post-install）。}';

    protected $description = '為指定的事件執行附加元件生命週期掛勾腳本。';

    /**
     * Runs every executable "addons/<name>/hooks/<event>" script for the given lifecycle event when addon hooks are enabled.
     */
    public function handle(): int
    {
        if (!config('addons.hooks_enabled')) {
            return self::SUCCESS;
        }

        $event = $this->argument('event');
        if (!Str::isMatch('/^[a-z0-9-]+$/', $event)) {
            $this->components->error("無效的掛勾事件名稱：{$event}");

            return self::INVALID;
        }

        $hooks = Collection::make(File::glob(base_path("addons/*/hooks/{$event}")) ?: [])
            ->filter(fn (string $hook) => is_executable($hook))
            ->values();

        if ($hooks->isEmpty()) {
            return self::SUCCESS;
        }

        if ($this->input->isInteractive() && !$this->confirm(
            sprintf('要為「%2$s」事件執行 %1$d 個附加元件掛勾腳本嗎？它們將以此程序的權限執行。', $hooks->count(), $event)
        )) {
            return self::SUCCESS;
        }

        $hooks->each($this->runHook(...));

        return self::SUCCESS;
    }

    /**
     * Streams a single hook's output, reporting a non-zero exit without aborting the remaining hooks.
     */
    private function runHook(string $hook): void
    {
        $this->components->info("正在執行附加元件掛勾：{$hook}");

        $result = Process::path(base_path())
            ->forever()
            ->run([$hook], fn (string $type, string $output) => $this->output->write($output));

        if ($result->failed()) {
            $this->components->warn("附加元件掛勾以錯誤結束：{$hook}");
        }
    }
}
