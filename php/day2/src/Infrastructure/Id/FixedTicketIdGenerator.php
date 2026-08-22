<?php

namespace App\Infrastructure\Id;

use App\Application\TicketIdGenerator;
use App\Ticket\TicketId;

// Всегда генерирует один и тот же тикет id в generate
class FixedTicketIdGenerator implements TicketIdGenerator
{
    readonly private string $fixedTicketId;
    public function __construct()
    {
        $this->fixedTicketId = bin2hex(random_bytes(16));
    }

    public function generate() : TicketId {
        return new TicketId($this->fixedTicketId);
    }
}