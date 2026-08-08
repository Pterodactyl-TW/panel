<?php

namespace Pterodactyl\Exceptions\Http\Server;

use Pterodactyl\Models\Server;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ServerStateConflictException extends ConflictHttpException
{
    /**
     * Exception thrown when the server is in an unsupported state for API access or
     * certain operations within the codebase.
     */
    public function __construct(Server $server, ?\Throwable $previous = null)
    {
        $message = '此伺服器目前處於不受支援的狀態，請稍後再試。';
        if ($server->isSuspended()) {
            $message = '此伺服器目前已被停權，所請求的功能無法使用。';
        } elseif ($server->node->isUnderMaintenance()) {
            $message = '此伺服器所在的節點目前正在維護中，所請求的功能無法使用。';
        } elseif (!$server->isInstalled()) {
            $message = '此伺服器尚未完成安裝流程，請稍後再試。';
        } elseif ($server->status === Server::STATUS_RESTORING_BACKUP) {
            $message = '此伺服器目前正在從備份還原，請稍後再試。';
        } elseif (!is_null($server->transfer)) {
            $message = '此伺服器目前正在轉移至新的機器，請稍後再試。';
        }

        parent::__construct($message, $previous);
    }
}
