<?php

namespace App\Http\Error;

enum ErrorCode: string
{
    case ValidationFailed = 'validation_failed';
    case TicketAlreadyExists = 'ticket_already_exists';
    case InternalError = 'internal_error';
}
