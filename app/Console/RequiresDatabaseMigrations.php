<?php

namespace Pterodactyl\Console;

/**
 * @mixin \Illuminate\Console\Command
 */
trait RequiresDatabaseMigrations
{
    /**
     * Checks if the migrations have finished running by comparing the last migration file.
     */
    protected function hasCompletedMigrations(): bool
    {
        /** @var \Illuminate\Database\Migrations\Migrator $migrator */
        $migrator = $this->getLaravel()->make('migrator');

        $files = $migrator->getMigrationFiles(database_path('migrations'));

        if (!$migrator->repositoryExists()) {
            return false;
        }

        if (array_diff(array_keys($files), $migrator->getRepository()->getRan())) {
            return false;
        }

        return true;
    }

    /**
     * Throw a massive error into the console to hopefully catch the users attention and get
     * them to properly run the migrations rather than ignoring all of the other previous
     * errors...
     */
    protected function showMigrationWarning(): void
    {
        $this->getOutput()->writeln('<options=bold>
| @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ |
|                                                                              |
|                       你的資料庫尚未正確完成遷移！                            |
|                                                                              |
| @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ |</>

你必須執行以下指令，才能完成資料庫遷移：

  <fg=green;options=bold>php artisan migrate --step --force</>

若不執行上方指令修正你的資料庫狀態，Pterodactyl Panel 將無法正常運作。
');

        $this->getOutput()->error('你必須先修正上方的錯誤才能繼續。');
    }
}
