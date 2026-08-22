<?php

namespace App\Infrastructure\Id;

use App\Application\TicketIdGenerator;
use App\Ticket\TicketId;

final class RandomTicketIdGenerator implements TicketIdGenerator
{
    public function generate(): TicketId
    {
        return new TicketId(bin2hex(random_bytes(16)));
    }
}