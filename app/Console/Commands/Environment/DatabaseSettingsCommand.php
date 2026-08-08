<?php

namespace Pterodactyl\Console\Commands\Environment;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\DatabaseManager;
use Pterodactyl\Traits\Commands\EnvironmentWriterTrait;

class DatabaseSettingsCommand extends Command
{
    use EnvironmentWriterTrait;

    protected $description = '配置 Panel 的資料庫設定。';

    protected $signature = 'p:environment:database
                            {--host= : MySQL 伺服器的連線位址。}
                            {--port= : MySQL 伺服器的連線連接埠。}
                            {--database= : 要使用的資料庫。}
                            {--username= : 連線時使用的使用者名稱。}
                            {--password= : 此資料庫使用的密碼。}';

    protected array $variables = [];

    /**
     * DatabaseSettingsCommand constructor.
     */
    public function __construct(private DatabaseManager $database, private Kernel $console)
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
        $this->output->note('強烈建議不要使用「localhost」作為你的資料庫主機，因為我們經常看到 socket 連線問題。若你想使用本機連線，應改用「127.0.0.1」。');
        $this->variables['DB_HOST'] = $this->option('host') ?? $this->ask(
            '資料庫主機',
            config('database.connections.mysql.host', '127.0.0.1')
        );

        $this->variables['DB_PORT'] = $this->option('port') ?? $this->ask(
            '資料庫連接埠',
            config('database.connections.mysql.port', 3306)
        );

        $this->variables['DB_DATABASE'] = $this->option('database') ?? $this->ask(
            '資料庫名稱',
            config('database.connections.mysql.database', 'panel')
        );

        $this->output->note('使用「root」帳號進行 MySQL 連線不僅相當不建議，此應用程式也不允許這麼做。你需要為此軟體另外建立一個 MySQL 使用者。');
        $this->variables['DB_USERNAME'] = $this->option('username') ?? $this->ask(
            '資料庫使用者名稱',
            config('database.connections.mysql.username', 'pterodactyl')
        );

        $askForMySQLPassword = true;
        if (!empty(config('database.connections.mysql.password')) && $this->input->isInteractive()) {
            $this->variables['DB_PASSWORD'] = config('database.connections.mysql.password');
            $askForMySQLPassword = $this->confirm('看起來你已經設定了 MySQL 連線密碼，你想要變更它嗎？');
        }

        if ($askForMySQLPassword) {
            $this->variables['DB_PASSWORD'] = $this->option('password') ?? $this->secret('資料庫密碼');
        }

        try {
            $this->testMySQLConnection();
        } catch (\PDOException $exception) {
            $this->output->error(sprintf('無法使用提供的憑證連接至 MySQL 伺服器，回傳的錯誤訊息為「%s」。', $exception->getMessage()));
            $this->output->error('你的連線憑證尚未儲存，在繼續之前你需要提供有效的連線資訊。');

            if ($this->confirm('要返回並重試嗎？')) {
                $this->database->disconnect('_pterodactyl_command_test');

                return $this->handle();
            }

            return 1;
        }

        $this->writeToEnvironment($this->variables);

        $this->info($this->console->output());

        return 0;
    }

    /**
     * Test that we can connect to the provided MySQL instance and perform a selection.
     */
    private function testMySQLConnection()
    {
        config()->set('database.connections._pterodactyl_command_test', [
            'driver' => 'mysql',
            'host' => $this->variables['DB_HOST'],
            'port' => $this->variables['DB_PORT'],
            'database' => $this->variables['DB_DATABASE'],
            'username' => $this->variables['DB_USERNAME'],
            'password' => $this->variables['DB_PASSWORD'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'strict' => true,
        ]);

        $this->database->connection('_pterodactyl_command_test')->getPdo();
    }
}
