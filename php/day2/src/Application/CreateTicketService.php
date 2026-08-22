<?php
declare(strict_types=1);

namespace App\Application;

use App\Clock\Clock;
use App\Ticket\Ticket;
use App\Ticket\TicketId;
use App\Ticket\TicketPriority;
use App\Ticket\TicketRepository;
use App\Ticket\TicketStatus;

final class CreateTicketService
{
    private TicketRepository $ticketRepository;
    private Clock $clock;
    private TicketIdGenerator $ticketIdGenerator;

    public function __construct(
        TicketRepository $repository,
        Clock $clock,
        TicketIdGenerator $ticketIdGenerator,
    ) {
        $this->ticketRepository = $repository;
        $this->clock = $clock;
        $this->ticketIdGenerator = $ticketIdGenerator;
    }

    public function create(string $title, TicketPriority $priority, TicketStatus $status, ?int $assigneeId = null) : Ticket
    {
        $createdAt = $this->clock->getTime();
        $id = $this->ticketIdGenerator->generate();

        $ticket = new Ticket($title, $priority, $status, $createdAt, $id, $assigneeId);
        $this->ticketRepository->add($ticket);

        return $ticket;
    }
}