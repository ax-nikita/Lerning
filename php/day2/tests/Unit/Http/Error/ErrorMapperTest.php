<?php

use App\Http\Error\ErrorCode;
use App\Http\Error\ErrorMapper;
use App\Ticket\TicketAlreadyExistsException;

const ERROR_MESSAGE = 'fake expection';

test('request_validation_error', function () {
    $error_mapper = new ErrorMapper();

    $exec = new \App\Http\Exception\RequestValidationException(ERROR_MESSAGE);

    $response = $error_mapper->map($exec);

    expect($response->body)->toBe([
        'error' => [
            'code' => ErrorCode::ValidationFailed->value,
            'message' => ERROR_MESSAGE,
        ]
    ]);

    expect($response->statusCode)->toBe(422);
});

test('ticket_alredy_exists_error', function () {
    $error_mapper = new ErrorMapper();

    $exec = new TicketAlreadyExistsException(ERROR_MESSAGE);

    $response = $error_mapper->map($exec);

    expect($response->body)->toBe([
        'error' => [
            'code' => ErrorCode::TicketAlreadyExists->value,
            'message' => ERROR_MESSAGE,
        ]
    ]);

    expect($response->statusCode)->toBe(409);
});

test('other_expection', function () {
    $error_mapper = new ErrorMapper();

    $exec = new \InvalidArgumentException(ERROR_MESSAGE);

    $response = $error_mapper->map($exec);

    expect($response->body)->toBe([
        'error' => [
            'code' => ErrorCode::InternalError->value,
            'message' => 'Internal server error', // тут фиксированно возвращаем что-бы не было лишней информации
        ]
    ]);

    expect($response->statusCode)->toBe(500);
});