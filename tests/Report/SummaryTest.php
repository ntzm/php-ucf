<?php

namespace Ntzm\Tests\PhpUcf\Comment;

use Ntzm\PhpUcf\Comment\Comment;
use Ntzm\PhpUcf\Report\Summary;
use Ntzm\PhpUcf\Violation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Stopwatch\StopwatchEvent;

final class SummaryTest extends TestCase
{
    public function testGetTimeInMilliseconds(): void
    {
        $event = $this->mockEvent(100, 0);

        $summary = new Summary([], $event);

        $this->assertEquals(100, $summary->getTimeInMilliseconds());
    }

    public function testGetTimeInSeconds(): void
    {
        $event = $this->mockEvent(10000, 0);

        $summary = new Summary([], $event);

        $this->assertEquals(10, $summary->getTimeInSeconds());
    }

    public function testGetMemoryInBytes(): void
    {
        $event = $this->mockEvent(0, 100);

        $summary = new Summary([], $event);

        $this->assertEquals(100, $summary->getMemoryInBytes());
    }

    public function testGetMemoryInMegabytes(): void
    {
        $event = $this->mockEvent(0, 3145728);

        $summary = new Summary([], $event);

        $this->assertEquals(3, $summary->getMemoryInMegabytes());
    }

    public function testGetViolations(): void
    {
        $violations = [
            new Violation(
                new Comment('', 1, Comment::TYPE_SINGLE_LINE),
                $this->createMock(SplFileInfo::class)
            ),
        ];

        $event = $this->mockEvent(0, 0);

        $summary = new Summary($violations, $event);

        $this->assertSame($violations, $summary->getViolations());
    }

    private function mockEvent(int $duration, int $memory): StopwatchEvent
    {
        $event = $this->createMock(StopwatchEvent::class);

        $event->method('getDuration')->willReturn($duration);
        $event->method('getMemory')->willReturn($memory);

        return $event;
    }
}
