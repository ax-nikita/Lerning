<?php
declare(strict_types=1);

namespace App\Ticket;

final class InMemoryTicketRepository implements TicketRepository
{
    /** @var array<string, Ticket> */
    private array $tickets = [];

    public function add(Ticket $ticket) : void
    {
        $ticketId = $ticket->id;

        if ($this->find($ticketId) !== null) {
            throw new TicketAlreadyExistsException("Ticket with id {$ticketId->value()} already exists.");
        }

        $this->tickets[$ticketId->value()] = $ticket;
    }

    public function find(TicketId $ticketId) : Ticket | null
    {
        $ticketId = $ticketId->value();

        if (isset($this->tickets[$ticketId])) {
            return $this->tickets[$ticketId];
        }

        return null;
    }
}