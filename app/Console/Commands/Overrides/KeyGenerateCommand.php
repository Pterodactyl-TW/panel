<?php

namespace Pterodactyl\Console\Commands\Overrides;

use Illuminate\Foundation\Console\KeyGenerateCommand as BaseKeyGenerateCommand;

class KeyGenerateCommand extends BaseKeyGenerateCommand
{
    /**
     * Override the default Laravel key generation command to throw a warning to the user
     * if it appears that they have already generated an application encryption key.
     */
    public function handle()
    {
        if (!empty(config('app.key')) && $this->input->isInteractive()) {
            $this->output->warning('看起來你已經設定過應用程式加密金鑰。繼續執行此程序將會覆蓋該金鑰，並導致所有既有的加密資料損毀。除非你清楚自己在做什麼，否則請勿繼續。');
            if (!$this->confirm('我理解執行此指令的後果，並承擔加密資料遺失的全部責任。')) {
                return;
            }

            if (!$this->confirm('你確定要繼續嗎？變更應用程式加密金鑰將會導致資料遺失。')) {
                return;
            }
        }

        parent::handle();
    }
}
