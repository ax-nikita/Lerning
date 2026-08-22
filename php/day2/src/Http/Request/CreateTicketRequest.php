<?php
declare(strict_types=1);

namespace App\Http\Request;

use App\Http\Exception\RequestValidationException;
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

    public const REQUEST_VALIDATION_ERROR = RequestValidationException::class;

    public static function fromArray(array $data): self
    {
        // Проверка обязательных ключей
        if (!isset($data['title'], $data['priority'], $data['status'])) {
            throw new (self::REQUEST_VALIDATION_ERROR)('data must be a valid array');
        }

        // Проверка корректности заголовка
        if (!is_string($data['title'])) {
            throw new (self::REQUEST_VALIDATION_ERROR)('title must be a string');
        }

        $title = trim($data['title']);

        if ($title === '') {
            throw new (self::REQUEST_VALIDATION_ERROR)('title cannot be empty');
        }

        // Проверка приоритетности
        if (!is_string($data['priority'])) {
            throw new (self::REQUEST_VALIDATION_ERROR)('priority must be a string');
        }

        $priority = TicketPriority::tryFrom($data['priority']);

        if ($priority === null) {
            throw new (self::REQUEST_VALIDATION_ERROR)('such a priority is unacceptable');
        }

        // проверка статуса
        if (!is_string($data['status'])) {
            throw new (self::REQUEST_VALIDATION_ERROR)('status must be a string');
        }

        $status = TicketStatus::tryFrom($data['status']);

        if ($status === null) {
            throw new (self::REQUEST_VALIDATION_ERROR)('such a status is unacceptable');
        }

        $assigneeId = $data['assigneeId'] ?? null;

        if ($assigneeId !== null && !is_int($assigneeId)) {
            throw new (self::REQUEST_VALIDATION_ERROR)('assigneeId must be an integer or null');
        }

        return new self($title, $priority, $status, $assigneeId);
    }
}