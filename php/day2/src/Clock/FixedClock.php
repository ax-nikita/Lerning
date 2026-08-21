<?php
declare(strict_types=1);

namespace App\Clock;

final class FixedClock implements Clock
{
    readonly \DateTimeImmutable $fixedTime;

    function __construct(string $fixedTime) {
        $this->fixedTime = new \DateTimeImmutable($fixedTime);
    }
    public function getTime(): \DateTimeImmutable
    {
        return $this->fixedTime;
    }
}