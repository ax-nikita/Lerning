<?php
declare(strict_types=1);

namespace App\Clock;

class FixedClock implements Clock
{
    public function getTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('2026-08-06 15:08:24');
    }
}