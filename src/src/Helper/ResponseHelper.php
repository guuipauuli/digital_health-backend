<?php

namespace App\Helper;

use \Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    public $code;

    public $message;

    public $result;

    public function __construct(int|Response $code, string $message, ?array $result = [])
    {
        $this->prepareResponse($code, $message, $result);
    }

    public function prepareResponse(int|Response $code, string $message, ?array $result = [])
    {
        $this->code = $code ?: Response::HTTP_INTERNAL_SERVER_ERROR;
        $this->message = $message;
        $this->result = $result;
    }
}
