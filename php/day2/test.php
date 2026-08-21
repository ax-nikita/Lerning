<?php

declare(strict_types=1);

use App\Ticket\InMemoryTicketRepository;
use App\Ticket\InvalidTicketIdException;
use App\Ticket\TicketAlreadyExistsException;
use App\Ticket\Ticket;
use App\Ticket\TicketId;
use App\Ticket\TicketPriority;
use App\Ticket\TicketStatus;
use App\User\Email;
use App\User\InvalidEmailException;

require __DIR__.'/vendor/autoload.php';

// Ticket happy path
try {
    $title = 'Тест';
    $priority = TicketPriority::High;
    $id = new TicketId(bin2hex(random_bytes(16)));
    $status = TicketStatus::New;
    $createdAt = new DateTimeImmutable;

    $ticket = new Ticket(
        $title,
        $priority,
        $status,
        $createdAt,
        $id
    );

    $test_result = true;
} catch (InvalidTicketIdException $th) {
    $test_result = false;
}

echo 'TicketId valid: '.($test_result ? 'PASS' : 'FAIL')."\r\n";

// Ticket negative test
try {
    $title = 'Тест';
    $priority = TicketPriority::High;
    $id = new TicketId('12');
    $status = TicketStatus::New;
    $createdAt = new DateTimeImmutable;

    $ticket = new Ticket(
        $title,
        $priority,
        $status,
        $createdAt,
        $id
    );

    $test_result = false;
} catch (InvalidTicketIdException $th) {
    $test_result = true;
}

echo 'TicketId invalid: '.($test_result ? 'PASS' : 'FAIL')."\r\n";

// Email happy path
try {
    $email = new Email('abrikos@gmail.com');
    $str = $email->value();
    $test_result = true;
} catch (InvalidEmailException) {
    $test_result = false;
}

echo 'Email valid: '.($test_result ? 'PASS' : 'FAIL')."\r\n";

// Email negative test
try {
    $email = new Email('1');
    $str = $email->value();
    $test_result = false;
} catch (InvalidEmailException) {
    $test_result = true;
}

echo 'Email invalid: '.($test_result ? 'PASS' : 'FAIL')."\r\n";

// Ticket happy path test add and find
try {
    $title = 'Тест';
    $priority = TicketPriority::High;
    $id = new TicketId(bin2hex(random_bytes(16)));
    $status = TicketStatus::New;
    $createdAt = new DateTimeImmutable;

    $ticket = new Ticket(
        $title,
        $priority,
        $status,
        $createdAt,
        $id
    );

    $repository = new InMemoryTicketRepository();

    $repository->add($ticket);

    $found_ticket = $repository->find($id);

    $test_result = $ticket->id->value() === $found_ticket->id->value();
} catch (TicketAlreadyExistsException $th) {
    $test_result = false;
}

echo 'Repository save/find: '.($test_result ? 'PASS' : 'FAIL')."\r\n";

// Ticket happy path not found

try {
    $title = 'Тест';
    $priority = TicketPriority::High;
    $id = bin2hex(random_bytes(16));
    $TicketId = new TicketId($id);
    $status = TicketStatus::New;
    $createdAt = new DateTimeImmutable;

    $ticket = new Ticket(
        $title,
        $priority,
        $status,
        $createdAt,
        $TicketId
    );

    $repository = new InMemoryTicketRepository();

    $repository->add($ticket);

    $second_id = $id;

    while ($second_id === $id) {
        $second_id = bin2hex(random_bytes(16));
    }

    $second_TicketId = new TicketId($second_id);

    $found_ticket = $repository->find($second_TicketId);

    $test_result = $found_ticket === null;
} catch (TicketAlreadyExistsException $th) {
    $test_result = false;
}

echo 'Repository not found: '.($test_result ? 'PASS' : 'FAIL')."\r\n";

// Ticket invalid add duplicate ticket

try {
    $title = 'Тест';
    $priority = TicketPriority::High;
    $id = new TicketId(bin2hex(random_bytes(16)));
    $status = TicketStatus::New;
    $createdAt = new DateTimeImmutable;

    $ticket = new Ticket(
        $title,
        $priority,
        $status,
        $createdAt,
        $id
    );

    $repository = new InMemoryTicketRepository();

    $repository->add($ticket);
    $repository->add($ticket);

    $test_result = false;
} catch (TicketAlreadyExistsException $th) {
    $test_result = true;
}

echo 'Repository duplicate: '.($test_result ? 'PASS' : 'FAIL')."\r\n";

// Test Repository DIP

function saveTicket(
    \App\Ticket\TicketRepository $repository,
    Ticket $ticket
): void
{
    $repository->add($ticket);
}

$repository = new InMemoryTicketRepository();

saveTicket($repository, $ticket);