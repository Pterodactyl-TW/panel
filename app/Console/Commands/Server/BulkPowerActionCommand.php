<?php

namespace Pterodactyl\Console\Commands\Server;

use Pterodactyl\Models\Server;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Factory as ValidatorFactory;
use Pterodactyl\Repositories\Wings\DaemonPowerRepository;
use Pterodactyl\Exceptions\Http\Connection\DaemonConnectionException;

class BulkPowerActionCommand extends Command
{
    protected $signature = 'p:server:bulk-power
                            {action : 要執行的電源操作 (start, stop, restart, kill)}
                            {--servers= : 要操作的伺服器 ID 清單（以逗號分隔，例如：ServerID1,ServerID2）}
                            {--nodes= : 要操作的節點 ID 清單（以逗號分隔，例如：NodeID1,NodeID2）}';

    protected $description = '對大量伺服器或節點群組一次執行批次電源管理操作。';

    /**
     * BulkPowerActionCommand constructor.
     */
    public function __construct(private DaemonPowerRepository $powerRepository, private ValidatorFactory $validator)
    {
        parent::__construct();
    }

    /**
     * Handle the bulk power request.
     *
     * @throws ValidationException
     */
    public function handle()
    {
        $action = $this->argument('action');
        $nodes = empty($this->option('nodes')) ? [] : explode(',', $this->option('nodes'));
        $servers = empty($this->option('servers')) ? [] : explode(',', $this->option('servers'));

        $validator = $this->validator->make([
            'action' => $action,
            'nodes' => $nodes,
            'servers' => $servers,
        ], [
            'action' => 'string|in:start,stop,kill,restart',
            'nodes' => 'array',
            'nodes.*' => 'integer|min:1',
            'servers' => 'array',
            'servers.*' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->all() as $message) {
                $this->output->error($message);
            }

            throw new ValidationException($validator);
        }

        $count = $this->getQueryBuilder($servers, $nodes)->count();
        if (!$this->confirm(trans('command/messages.server.power.confirm', ['action' => $action, 'count' => $count])) && $this->input->isInteractive()) {
            return;
        }

        $bar = $this->output->createProgressBar($count);
        $powerRepository = $this->powerRepository;
        $this->getQueryBuilder($servers, $nodes)->each(function (Server $server) use ($action, $powerRepository, &$bar) {
            $bar->clear();

            try {
                $powerRepository->setServer($server)->send($action);
            } catch (DaemonConnectionException $exception) {
                $this->output->error(trans('command/messages.server.power.action_failed', [
                    'name' => $server->name,
                    'id' => $server->id,
                    'node' => $server->node->name,
                    'message' => $exception->getMessage(),
                ]));
            }

            $bar->advance();
            $bar->display();
        });

        $this->line('');
    }

    /**
     * Returns the query builder instance that will return the servers that should be affected.
     */
    protected function getQueryBuilder(array $servers, array $nodes): Builder
    {
        $instance = Server::query()->whereNull('status');

        if (!empty($nodes) && !empty($servers)) {
            $instance->whereIn('id', $servers)->orWhereIn('node_id', $nodes);
        } elseif (empty($nodes) && !empty($servers)) {
            $instance->whereIn('id', $servers);
        } elseif (!empty($nodes) && empty($servers)) { // @phpstan-ignore empty.variable
            $instance->whereIn('node_id', $nodes);
        }

        return $instance->with('node');
    }
}
