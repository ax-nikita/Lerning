<?php
declare(strict_types=1);

namespace App\Clock;

final class FixedClock implements Clock
{
    readonly string $fixedTime;

    function __construct(string $fixedTime) {
        $this->fixedTime = $fixedTime;
    }
    public function getTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->fixedTime);
    }
}