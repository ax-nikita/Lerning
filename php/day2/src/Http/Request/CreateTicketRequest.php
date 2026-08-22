<?php
declare(strict_types=1);

namespace App\Http\Request;

use App\Ticket\TicketPriority;
use App\Ticket\TicketStatus;

class CreateTicketRequest
{
    readonly private(set) string $title;
    readonly private(set) TicketPriority $priority;
    readonly private(set) TicketStatus  $status;
    readonly private(set) ?int $assigneeId;

    private function __construct(string $title, TicketPriority $priority, TicketStatus $status, ?int $assigneeId = null)
    {
        $this->title = $title;
        $this->priority = $priority;
        $this->status = $status;
        $this->assigneeId = $assigneeId;
    }

    public static function fromArray(array $data): self
    {
        // Проверка обязательных ключей
        if (!isset($data['title'], $data['priority'], $data['status'])) {
            throw new \InvalidArgumentException('data must be a valid array');
        }

        // Проверка корректности заголовка
        if (!is_string($data['title'])) {
            throw new \InvalidArgumentException('title must be a string');
        }

        $title = trim($data['title']);

        if ($title === '') {
            throw new \InvalidArgumentException('title cannot be empty');
        }

        // Проверка приоритетности
        if (!is_string($data['priority'])) {
            throw new \InvalidArgumentException('priority must be a string');
        }

        $priority = TicketPriority::tryFrom($data['priority']);

        if ($priority === null) {
            throw new \InvalidArgumentException('such a priority is unacceptable');
        }

        // проверка статуса
        if (!is_string($data['status'])) {
            throw new \InvalidArgumentException('status must be a string');
        }

        $status = TicketStatus::tryFrom($data['status']);

        if ($status === null) {
            throw new \InvalidArgumentException('such a status is unacceptable');
        }

        $assigneeId = $data['assigneeId'] ?? null;

        if ($assigneeId !== null && !is_int($assigneeId)) {
            throw new \InvalidArgumentException('assigneeId must be an integer or null');
        }

        return new self($title, $priority, $status, $assigneeId);
    }
}