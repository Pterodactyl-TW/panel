<?php

namespace Pterodactyl\Exceptions\Http\Server;

use Pterodactyl\Exceptions\DisplayException;

class FileSizeTooLargeException extends DisplayException
{
    /**
     * FileSizeTooLargeException constructor.
     */
    public function __construct()
    {
        parent::__construct('你嘗試開啟的檔案過大，無法在檔案編輯器中檢視。');
    }
}
