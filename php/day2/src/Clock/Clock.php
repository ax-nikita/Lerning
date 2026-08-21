<?php

namespace App\Clock;

interface Clock
{
    public function getTime(): \DateTimeImmutable;
}