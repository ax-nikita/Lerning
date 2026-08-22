<?php

use App\Http\Error\ErrorCode;

test('iternal error code correct', function () {
   expect(ErrorCode::InternalError->value)->toBe('internal_error');
});

test('ticket already exists code correct', function () {
    expect(ErrorCode::TicketAlreadyExists->value)->toBe('ticket_already_exists');
});

test('validation failed code correct', function () {
    expect(ErrorCode::ValidationFailed->value)->toBe('validation_failed');
});