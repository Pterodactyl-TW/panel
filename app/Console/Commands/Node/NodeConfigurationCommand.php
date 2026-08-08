<?php

namespace Pterodactyl\Console\Commands\Node;

use Pterodactyl\Models\Node;
use Illuminate\Console\Command;

class NodeConfigurationCommand extends Command
{
    protected $signature = 'p:node:configuration
                            {node : 要傳回組態設定的節點 ID 或 UUID。}
                            {--format=yaml : 輸出格式，選項為 "yaml" 與 "json"。}';

    protected $description = '顯示指定節點的組態設定。';

    public function handle(): int
    {
        $column = ctype_digit((string) $this->argument('node')) ? 'id' : 'uuid';

        /** @var Node $node */
        $node = Node::query()->where($column, $this->argument('node'))->firstOr(function () {
            $this->error('所選的節點不存在。');

            exit(1);
        });

        $format = $this->option('format');
        if (!in_array($format, ['yaml', 'yml', 'json'])) {
            $this->error('指定的格式無效，有效選項為 "yaml" 與 "json"。');

            return 1;
        }

        if ($format === 'json') {
            $this->output->write($node->getJsonConfiguration(true));
        } else {
            $this->output->write($node->getYamlConfiguration());
        }

        $this->output->newLine();

        return 0;
    }
}
