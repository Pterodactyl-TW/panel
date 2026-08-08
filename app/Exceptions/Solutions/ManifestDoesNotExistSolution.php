<?php

namespace Pterodactyl\Exceptions\Solutions;

use Spatie\Ignition\Contracts\Solution;

class ManifestDoesNotExistSolution implements Solution
{
    public function getSolutionTitle(): string
    {
        return 'manifest.json 檔案尚未產生';
    }

    public function getSolutionDescription(): string
    {
        return '請先執行 yarn run build:production 以建置前端資源。';
    }

    public function getDocumentationLinks(): array
    {
        return [
            '文件' => 'https://github.com/Pterodactyl-TW/panel/blob/1.0-develop/package.json',
        ];
    }
}
