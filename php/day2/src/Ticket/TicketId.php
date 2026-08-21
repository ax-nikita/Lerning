<?php

namespace App\Ticket;

final readonly class TicketId
{
    private string $id;

    public function __construct(string $id)
    {
        if (! ctype_xdigit($id) || strlen($id) !== 32) {
            throw new InvalidTicketIdException('Invalid ticket id: '.$id);
        }

        $this->id = $id;
    }

    public function value(): string
    {
        return $this->id;
    }
}
