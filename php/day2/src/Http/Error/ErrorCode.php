<?php
declare(strict_types=1);

namespace App\Http\Error;

enum ErrorCode: string
{
    case ValidationFailed = 'validation_failed';
    case TicketAlreadyExists = 'ticket_already_exists';
    case InternalError = 'internal_error';
}
