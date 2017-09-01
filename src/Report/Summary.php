<?php

namespace Ntzm\PhpUcf\Report;

use Symfony\Component\Stopwatch\StopwatchEvent;

final class Summary
{
    private $violations;
    private $event;

    public function __construct(array $violations, StopwatchEvent $event)
    {
        $this->violations = $violations;
        $this->event = $event;
    }

    public function getViolations(): array
    {
        return $this->violations;
    }

    public function getTimeInMilliseconds(): int
    {
        return $this->event->getDuration();
    }

    public function getTimeInSeconds(): float
    {
        return $this->event->getDuration() / 1000;
    }

    public function getMemoryInBytes(): int
    {
        return $this->event->getMemory();
    }

    public function getMemoryInMegabytes(): float
    {
        return $this->event->getMemory() / 1024 / 1024;
    }
}
