<?php

namespace App\Http\Error;

use App\Http\Exception\RequestValidationException;
use App\Http\Response\HttpResponse;
use App\Ticket\TicketAlreadyExistsException;

final class ErrorMapper
{
    function map(\Throwable $exception): HttpResponse
    {
        switch ($exception::class) {
            case RequestValidationException::class:
                return new HttpResponse(422, [
                    'error' => [
                        'code' => ErrorCode::ValidationFailed->value,
                        'message' => $exception->getMessage(),
                    ]
                ]);
                break;
            case TicketAlreadyExistsException::class:
                return new HttpResponse(409, [
                    'error' => [
                        'code' => ErrorCode::TicketAlreadyExists->value,
                        'message' => $exception->getMessage(),
                    ]
                ]);
                break;
            default:
                return new HttpResponse(500, [
                    'error' => [
                        'code' => ErrorCode::InternalError->value,
                        'message' => 'Internal server error',
                    ]
                ]);
                break;
        }
    }
}