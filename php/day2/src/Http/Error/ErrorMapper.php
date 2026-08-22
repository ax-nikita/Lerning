<?php
declare(strict_types=1);

namespace App\Http\Error;

use App\Http\Exception\RequestValidationException;
use App\Http\Response\HttpResponse;
use App\Ticket\TicketAlreadyExistsException;

final class ErrorMapper
{
    public function map(\Throwable $exception): HttpResponse
    {
        return match (true) {
            $exception instanceof RequestValidationException => new HttpResponse(422, [
                'error' => [
                    'code' => ErrorCode::ValidationFailed->value,
                    'message' => $exception->getMessage(),
                ]
            ]),
            $exception instanceof TicketAlreadyExistsException => new HttpResponse(409, [
                'error' => [
                    'code' => ErrorCode::TicketAlreadyExists->value,
                    'message' => $exception->getMessage(),
                ]
            ]),
            $exception instanceof \Exception => new HttpResponse(500, [
                'error' => [
                    'code' => ErrorCode::InternalError->value,
                    'message' => 'Internal server error',
                ]
            ])
        };
    }
}