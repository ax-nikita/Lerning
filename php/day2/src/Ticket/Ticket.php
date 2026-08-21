<?php

declare(strict_types=1);

namespace App\Ticket;

final class Ticket
{
    // Identity / metadata
    public private(set) readonly TicketId $id;

    public private(set) readonly \DateTimeImmutable $createdAt;

    // State
    public private(set) string $title;

    public private(set) TicketPriority $priority;

    public private(set) TicketStatus $status;

    public private(set) readonly ?int $assigneeId;

    // Computed properties
    public int $priorityWeight {
        get => $this->priority->weight();
    }

    // Methods
    public function __construct(
        string $title,
        TicketPriority $priority,
        TicketStatus $status,
        \DateTimeImmutable $createdAt,
        TicketId $id,
        ?int $assigneeId = null
    ) {
        $title = trim($title);

        if ($title === '') {
            throw new \InvalidArgumentException('Не верно задано название тикета');
        }

        $this->title = $title;
        $this->createdAt = $createdAt;
        $this->priority = $priority;
        $this->status = $status;
        $this->assigneeId = $assigneeId;
        $this->id = $id;
    }

    /**
     * @return array{
     *     id: string,
     *     title: string,
     *     priority: string,
     *     status: string,
     *     assignee_id: int|null,
     *     created_at: string
     * }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'title' => $this->title,
            'priority' => $this->priority->value,
            'status' => $this->status->value,
            'assignee_id' => $this->assigneeId,
            'created_at' => $this->createdAt->format(DATE_ATOM),
        ];
    }
}
