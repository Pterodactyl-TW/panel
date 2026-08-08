<?php

namespace Pterodactyl\Exceptions\Service\Database;

use Pterodactyl\Exceptions\DisplayException;

class NoSuitableDatabaseHostException extends DisplayException
{
    /**
     * NoSuitableDatabaseHostException constructor.
     */
    public function __construct()
    {
        parent::__construct('找不到符合此伺服器需求的資料庫主機。');
    }
}
