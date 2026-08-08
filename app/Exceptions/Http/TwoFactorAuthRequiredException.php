<?php

namespace Pterodactyl\Exceptions\Http;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class TwoFactorAuthRequiredException extends HttpException implements HttpExceptionInterface
{
    /**
     * TwoFactorAuthRequiredException constructor.
     */
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, '此帳號必須啟用兩步驟驗證才能存取此端點。', $previous);
    }
}
