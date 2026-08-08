<?php

namespace Database\Seeders;

use Pterodactyl\Models\Egg;
use Pterodactyl\Models\Nest;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Pterodactyl\Services\Eggs\Sharing\EggImporterService;
use Pterodactyl\Services\Eggs\Sharing\EggUpdateImporterService;

class EggSeeder extends Seeder
{
    protected EggImporterService $importerService;

    protected EggUpdateImporterService $updateImporterService;

    /**
     * 對應「Nest 名稱」與其 Egg 檔案所在目錄（kebab-case）的關係。
     *
     * @var array<string, string>
     */
    public static array $import = [
        'Minecraft' => 'minecraft',
        'Source Engine' => 'source-engine',
        '語音伺服器' => 'voice-servers',
        'Rust' => 'rust',
    ];

    /**
     * EggSeeder constructor.
     */
    public function __construct(
        EggImporterService $importerService,
        EggUpdateImporterService $updateImporterService,
    ) {
        $this->importerService = $importerService;
        $this->updateImporterService = $updateImporterService;
    }

    /**
     * Run the egg seeder.
     */
    public function run()
    {
        foreach (static::$import as $nest => $directory) {
            /* @noinspection PhpParamsInspection */
            $this->parseEggFiles(
                Nest::query()->where('author', 'support@pterodactyl.io')->where('name', $nest)->firstOrFail(),
                $directory
            );
        }
    }

    /**
     * Loop through the list of egg files and import them.
     */
    protected function parseEggFiles(Nest $nest, string $directory)
    {
        $files = new \DirectoryIterator(database_path('Seeders/eggs/' . $directory));

        $this->command->alert('正在更新 Nest 的 Egg：' . $nest->name);
        /** @var \DirectoryIterator $file */
        foreach ($files as $file) {
            if (!$file->isFile() || !$file->isReadable()) {
                continue;
            }

            $decoded = json_decode(file_get_contents($file->getRealPath()), true, 512, JSON_THROW_ON_ERROR);
            $file = new UploadedFile($file->getPathname(), $file->getFilename(), 'application/json');

            $egg = $nest->eggs()
                ->where('author', $decoded['author'])
                ->where('name', $decoded['name'])
                ->first();

            if ($egg instanceof Egg) {
                $this->updateImporterService->handle($egg, $file);
                $this->command->info('已更新 ' . $decoded['name']);
            } else {
                $this->importerService->handle($file, $nest->id);
                $this->command->comment('已建立 ' . $decoded['name']);
            }
        }

        $this->command->line('');
    }
}
