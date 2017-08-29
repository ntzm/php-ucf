<?php

namespace Ntzm\UselessCommentFinder\Report;

final class Summary
{
    private $violations;
    private $timeInMilliseconds;
    private $memoryInBytes;

    public function __construct(array $violations, int $timeInMilliseconds, int $memoryInBytes)
    {
        $this->violations = $violations;
        $this->timeInMilliseconds = $timeInMilliseconds;
        $this->memoryInBytes = $memoryInBytes;
    }

    public function getViolations(): array
    {
        return $this->violations;
    }

    public function getTimeInMilliseconds(): int
    {
        return $this->timeInMilliseconds;
    }

    public function getTimeInSeconds(): float
    {
        return $this->timeInMilliseconds / 1000;
    }

    public function getMemoryInBytes(): int
    {
        return $this->memoryInBytes;
    }

    public function getMemoryInMegabytes(): float
    {
        return $this->memoryInBytes / 1024 / 1024;
    }
}
