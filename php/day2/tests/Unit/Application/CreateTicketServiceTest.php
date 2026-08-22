<?php

use App\Infrastructure\Id\FixedTicketIdGenerator;
use App\Ticket\InMemoryTicketRepository;
use App\Ticket\TicketPriority;
use App\Ticket\TicketStatus;

use App\Application\CreateTicketService;

use App\Clock\FixedClock;

test('creates and stores ticket with fixed time', function () {
    $title = 'Тест';
    $priority = TicketPriority::High;
    $status = TicketStatus::New;

    $createdAt = new \DateTimeImmutable('2026-08-06 15:08:24');
    $repository = new InMemoryTicketRepository();
    $ticketIdGenerator = new FixedTicketIdGenerator();
    $clock = new FixedClock($createdAt);

    $id = $ticketIdGenerator->generate();

    $service = new CreateTicketService($repository, $clock, $ticketIdGenerator);

    $ticket = $service->create($title, $priority, $status);

    // Test ticket fields
    expect($ticket->createdAt)->toBe($createdAt);
    expect($ticket->title)->toBe($title);
    expect($ticket->status)->toBe($status);
    expect($ticket->priority)->toBe($priority);
    expect($ticket->id->value())->toBe($id->value());
    expect($ticket->assigneeId)->toBe(null);

    // Test ticket exist in repository
    expect($repository->find($id))->toBe($ticket);

    // part two, test assigneeId
    $repository->clear();

    $assigneeId = 12;
    $ticket = $service->create($title, $priority, $status, $assigneeId);

    expect($ticket->assigneeId)->toBe($assigneeId);
});