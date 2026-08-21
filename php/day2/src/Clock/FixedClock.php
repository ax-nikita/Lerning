<?php
declare(strict_types=1);

namespace App\Clock;

final class FixedClock implements Clock
{
    readonly \DateTimeImmutable $fixedTime;

    function __construct(\DateTimeImmutable $fixedTime) {
        $this->fixedTime = $fixedTime;
    }
    public function getTime(): \DateTimeImmutable
    {
        return $this->fixedTime;
    }
}