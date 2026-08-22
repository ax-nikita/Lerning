<?php

namespace App\Application;

use App\Ticket\TicketId;

interface TicketIdGenerator
{
    public function generate(): TicketId;
}