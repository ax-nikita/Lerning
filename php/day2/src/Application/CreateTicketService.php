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

    public function __construct(
        TicketRepository $repository,
        Clock $clock,
    ) {
        $this->ticketRepository = $repository;
        $this->clock = $clock;
    }

    public function create(string $title, TicketPriority $priority, TicketId $id, TicketStatus $status, ?int $assigneeId = null) : Ticket
    {
        $createdAt = $this->clock->getTime();

        $ticket = new Ticket($title, $priority, $status, $createdAt, $id, $assigneeId);
        $this->ticketRepository->add($ticket);

        return $ticket;
    }
}