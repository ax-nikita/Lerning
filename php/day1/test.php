<?php
declare(strict_types=1);

enum TicketStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
}

enum TicketPriority: string
{
    case Low = 'low';
    case Normal = 'normal';
    case High = 'high';

    public function weight(): int
    {
        return match ($this) {
            self::Low => 1,
            self::Normal => 2,
            self::High => 3,
        };
    }
}

function normalizeAssigneeId(mixed $raw): ?int
{
    if ($raw === null || $raw === '') {
        return null;
    }

    $validated = filter_var($raw, FILTER_VALIDATE_INT);

    if ($validated === false) {
        throw new InvalidArgumentException(
            'Идентификатор исполнителя должен быть целым числом.'
        );
    }

    if ($validated <= 0) {
        throw new InvalidArgumentException(
            'Идентификатор исполнителя должен быть больше нуля.'
        );
    }

    return $validated;
}

function normalizeTitle(string $raw_title): string
{
    return trim($raw_title)
            |> mb_strtoupper(...)
            |> (fn(string $value): string => preg_replace('/\s+/', ' ', $value));
}

final class Ticket
{
    // Fields
    private(set) string $title;
    private(set) TicketPriority $priority;
    public int $priorityWeight {
        get => $this->priority->weight();
    }
    private(set) TicketStatus $status;
    readonly private(set) ?int $assigneeId;

    readonly private(set) DateTimeImmutable $createdAt;

    readonly private(set) string $id;

    // Methods
    public function __construct(
        string            $title,
        TicketPriority    $priority,
        TicketStatus      $status,
        DateTimeImmutable $createdAt,
        string            $id,
        ?int              $assigneeId = null
    )
    {
        $title = trim($title);

        if ($title === '') {
            throw new InvalidArgumentException('Не верно задано название тикета');
        }

        $this->title = $title;
        $this->createdAt = $createdAt;
        $this->priority = $priority;
        $this->status = $status;
        $this->assigneeId = $assigneeId;
        $this->id = $id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'priority' => $this->priority->value,
            'status' => $this->status->value,
            'assignee_id' => $this->assigneeId,
            'created_at' => $this->createdAt->format(DATE_ATOM),
        ];
    }
}

$result = [
    'success' => false
];

try {
    $date = new DateTimeImmutable();
    $id = bin2hex(random_bytes(16));
    $assigneeId = normalizeAssigneeId($_POST['assignee_id'] ?? null);

    $ticket = new Ticket(
        normalizeTitle($_POST['title'] ?? ''),
        TicketPriority::from($_POST['priority'] ?? 'normal'),
        TicketStatus::from($_POST['status'] ?? 'new'),
        $date,
        $id,
        $assigneeId,
    );

    $result['success'] = true;
    $result['ticket'] = $ticket->toArray();
} catch (ValueError|InvalidArgumentException $e) {
    $result['error'] = $e->getMessage();
}


echo json_encode($result);