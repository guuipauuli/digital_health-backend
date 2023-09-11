<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    public int $code;

    public ?string $message;

    public ?array $result;

    public function __construct(null|int $code = null, null|string $message = null, ?array $result = [])
    {
        $this->prepareResponse($code, $message, $result);

        return $this;
    }

    public function prepareResponse(null|int $code, ?string $message, ?array $result = []): static
    {
        $this->code = $code ?: Response::HTTP_INTERNAL_SERVER_ERROR;
        $this->message = $message;
        $this->result = $result;

        return $this;
    }

    public function toJson() {
        return new JsonResponse($this);
    }
}
