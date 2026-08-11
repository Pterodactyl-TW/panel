<?php

namespace Pterodactyl\Console\Commands\Node;

use Illuminate\Console\Command;
use Pterodactyl\Services\Nodes\NodeCreationService;

class MakeNodeCommand extends Command
{
    protected $signature = 'p:node:make
                            {--name= : 用來識別此節點的名稱。}
                            {--description= : 用來識別此節點的描述。}
                            {--locationId= : 有效的 locationId。}
                            {--fqdn= : 用於連接 Wings 的網域名稱（例如 node.example.com）。僅在此節點未使用 SSL 時，才可以使用 IP 位址。}
                            {--public= : 此節點應為公開還是私人？（公開=1 / 私人=0）。}
                            {--scheme= : 應使用哪種通訊協定？（啟用 SSL=https / 停用 SSL=http）。}
                            {--proxy= : Wings 是否位於代理伺服器後方？（是=1 / 否=0）。}
                            {--maintenance= : 是否應啟用維護模式？（啟用=1 / 停用=0）。}
                            {--maxMemory= : 設定最大記憶體容量。}
                            {--overallocateMemory= : 輸入要超額配置的記憶體容量（百分比，或 -1 表示無限制超額配置）。}
                            {--maxDisk= : 設定最大磁碟容量。}
                            {--overallocateDisk= : 輸入要超額配置的磁碟容量（百分比，或 -1 表示無限制超額配置）。}
                            {--uploadSize= : 輸入最大上傳檔案大小。}
                            {--daemonListeningPort= : 輸入 Wings 監聽連接埠。}
                            {--daemonSFTPPort= : 輸入 Wings SFTP 監聽連接埠。}
                            {--daemonBase= : 輸入基礎資料夾路徑。}';

    protected $description = '透過 CLI 在系統中建立一個新節點。';

    /**
     * MakeNodeCommand constructor.
     */
    public function __construct(private NodeCreationService $creationService)
    {
        parent::__construct();
    }

    /**
     * Handle the command execution process.
     *
     * @throws \Pterodactyl\Exceptions\Model\DataValidationException
     */
    public function handle()
    {
        $data['name'] = $this->option('name') ?? $this->ask('請輸入用來與其他節點區別的簡短識別名稱');
        $data['description'] = $this->option('description') ?? $this->ask('請輸入用來識別此節點的描述');
        $data['location_id'] = $this->option('locationId') ?? $this->ask('請輸入有效的位置 ID');
        $data['scheme'] = $this->option('scheme') ?? $this->anticipate(
            '請輸入 https 以使用 SSL，或輸入 http 以使用非 SSL 連線',
            ['https', 'http'],
            'https'
        );
        $data['fqdn'] = $this->option('fqdn') ?? $this->ask('請輸入用於連接 Wings 的網域名稱（例如 node.example.com）。僅在此節點未使用 SSL 時，才可以使用 IP 位址');
        $data['public'] = $this->option('public') ?? $this->confirm('此節點是否應為公開？請注意，若將節點設為私人，將無法對此節點啟用自動部署。', true);
        $data['behind_proxy'] = $this->option('proxy') ?? $this->confirm('你的 FQDN 是否位於代理伺服器後方？');
        $data['maintenance_mode'] = $this->option('maintenance') ?? $this->confirm('是否應啟用維護模式？');
        $data['memory'] = $this->option('maxMemory') ?? $this->ask('請輸入最大記憶體容量');
        $data['memory_overallocate'] = $this->option('overallocateMemory') ?? $this->ask('請輸入要超額配置的記憶體容量，-1 將停用檢查，0 則會禁止建立新伺服器');
        $data['disk'] = $this->option('maxDisk') ?? $this->ask('請輸入最大磁碟容量');
        $data['disk_overallocate'] = $this->option('overallocateDisk') ?? $this->ask('請輸入要超額配置的磁碟容量，-1 將停用檢查，0 則會禁止建立新伺服器');
        $data['upload_size'] = $this->option('uploadSize') ?? $this->ask('請輸入最大上傳檔案大小', '100');
        $data['daemonListen'] = $this->option('daemonListeningPort') ?? $this->ask('請輸入 Wings 監聽連接埠', '8080');
        $data['daemonSFTP'] = $this->option('daemonSFTPPort') ?? $this->ask('請輸入 Wings SFTP 監聽連接埠', '2022');
        $data['daemonBase'] = $this->option('daemonBase') ?? $this->ask('請輸入基礎資料夾路徑', '/var/lib/pterodactyl/volumes');

        $node = $this->creationService->handle($data);
        $this->line('已成功在位置 ' . $data['location_id'] . ' 建立名為 ' . $data['name'] . ' 的新節點，其 ID 為 ' . $node->id . '。');
    }
}
