<?php

namespace App\Http\Response;

final class HttpResponse
{
    readonly public int $statusCode;
    readonly public array $body;

    function __construct(int $statusCode, array $body) {
        $this->statusCode = $statusCode;
        $this->body = $body;
    }
}