<?php

namespace Ntzm\Tests\PhpUcf\Comment;

use Ntzm\PhpUcf\Comment\Comment;
use Ntzm\PhpUcf\Report\Summary;
use Ntzm\PhpUcf\Violation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\SplFileInfo;

final class SummaryTest extends TestCase
{
    public function testGetTimeInMilliseconds(): void
    {
        $summary = new Summary([], 100, 0);

        $this->assertEquals(100, $summary->getTimeInMilliseconds());
    }

    public function testGetTimeInSeconds(): void
    {
        $summary = new Summary([], 10000, 0);

        $this->assertEquals(10, $summary->getTimeInSeconds());
    }

    public function testGetMemoryInBytes(): void
    {
        $summary = new Summary([], 0, 100);

        $this->assertEquals(100, $summary->getMemoryInBytes());
    }

    public function testGetMemoryInMegabytes(): void
    {
        $summary = new Summary([], 0, 3145728);

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

        $summary = new Summary($violations, 0, 0);

        $this->assertSame($violations, $summary->getViolations());
    }
}
