<?php

use App\Http\Exception\RequestValidationException;
use App\Http\Request\CreateTicketRequest;

use App\Ticket\TicketStatus;
use App\Ticket\TicketPriority;

test('creates from valid array', function () {
    $dot = CreateTicketRequest::fromArray([
        "title" => "Test Title",
        "priority" => TicketPriority::High->value,
        "status" => TicketStatus::New->value,
    ]);

    expect($dot)->toBeInstanceOf(CreateTicketRequest::class);

    expect($dot->title)->toBe("Test Title");
    expect($dot->status)->toBe(TicketStatus::New);
    expect($dot->priority)->toBe(TicketPriority::High);
    expect($dot->assigneeId)->toBe(null);
});

test('creates from not valid and broken array values', function () {
    $template_array = [
        "title" => "Test Title",
        "priority" => TicketPriority::High->value,
        "status" => TicketStatus::New->value,
        "assigneeId" => 12,
    ];

    foreach ($template_array as $key => $_) {
        $not_valid_array = $template_array;
        $not_valid_array[$key] = array("fake");

        expect(
            fn () => CreateTicketRequest::fromArray($not_valid_array)
        )->toThrow(RequestValidationException::class);

        $brocken_array = $not_valid_array;
        unset($brocken_array[$key]);

        // для assigneeId проверка не наличия не нужна, на оборот создание должно быть валидным н
        if ($key === 'assigneeId') {
            $dot = CreateTicketRequest::fromArray($brocken_array);
            expect($dot)->toBeInstanceOf(CreateTicketRequest::class);
            expect($dot->assigneeId)->toBe(null);
        } else {
            expect(
                fn () => CreateTicketRequest::fromArray($brocken_array)
            )->toThrow(RequestValidationException::class);
        }
    }
});