<?php

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

    foreach ($template_array as $key => $value) {
        $not_valid_array = $template_array;
        $not_valid_array[$key] = array("fake");

        try {
            $dot = CreateTicketRequest::fromArray($not_valid_array);
        } catch (\InvalidArgumentException $exception) {
            $dot = "{$key} not valid error success";
        }

        expect($dot)->toBe("{$key} not valid error success");

        $brocken_array = $not_valid_array;
        unset($brocken_array[$key]);

        try {
            $dot = CreateTicketRequest::fromArray($not_valid_array);
        } catch (\InvalidArgumentException $exception) {
            $dot = "{$key} broken error success";
        }

        expect($dot)->toBe("{$key} broken error success");
    }
});