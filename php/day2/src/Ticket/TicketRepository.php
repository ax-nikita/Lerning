<?php
declare(strict_types=1);

namespace App\Ticket;

interface TicketRepository
{
    public function add(Ticket $ticket) : void;

    public function find(TicketId $ticketId) : Ticket | null;

    public function clear() : void;

    public function delete(TicketId $ticketId) : void;
}