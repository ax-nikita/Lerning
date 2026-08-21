<?php
declare(strict_types=1);

namespace App\Clock;

final class SystemClock implements Clock
{
    public function getTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}