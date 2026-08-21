<?php

use App\Ticket\InMemoryTicketRepository;
use App\Ticket\TicketId;
use App\Ticket\TicketPriority;
use App\Ticket\TicketStatus;

use App\Application\CreateTicketService;

use App\Clock\FixedClock;

test('creates and stores ticket with fixed time', function () {
    $title = 'Тест';
    $priority = TicketPriority::High;
    $id = new TicketId(bin2hex(random_bytes(16)));
    $status = TicketStatus::New;

    $createdAt = new \DateTimeImmutable('2026-08-06 15:08:24');
    $repository = new InMemoryTicketRepository();
    $clock = new FixedClock($createdAt);

    $service = new CreateTicketService($repository, $clock);

    $ticket = $service->create($title, $priority, $id, $status);

    // Test ticket fields
    expect($ticket->createdAt)->toBe($createdAt);
    expect($ticket->title)->toBe($title);
    expect($ticket->status)->toBe($status);
    expect($ticket->priority)->toBe($priority);
    expect($ticket->id)->toBe($id);
    expect($ticket->assigneeId)->toBe(null);

    // Test ticket exist in repository
    expect($repository->find($id))->toBe($ticket);

    // part two, test assigneeId
    $id = new TicketId(bin2hex(random_bytes(16)));

    $assigneeId = 12;
    $ticket = $service->create($title, $priority, $id, $status, $assigneeId);

    expect($ticket->assigneeId)->toBe($assigneeId);
});