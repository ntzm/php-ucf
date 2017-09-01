<?php

namespace Ntzm\Tests\PhpUcf\Comment;

use Ntzm\PhpUcf\Comment\Comment;
use Ntzm\PhpUcf\Report\JsonReporter;
use Ntzm\PhpUcf\Report\Summary;
use Ntzm\PhpUcf\Violation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Stopwatch\StopwatchEvent;

final class JsonReporterTest extends TestCase
{
    public function testReporter()
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('getRealPath')->willReturn('/app/thing.php');

        $violations = [
            new Violation(
                new Comment('', 1, Comment::TYPE_SINGLE_LINE),
                $file
            ),
        ];

        $event = $this->createMock(StopwatchEvent::class);
        $event->method('getDuration')->willReturn(100);
        $event->method('getMemory')->willReturn(3145728);

        $summary = new Summary($violations, $event);

        $expected = [
            'stats' => [
                'time' => 100,
                'memory' => 3145728,
            ],
            'violations' => [
                [
                    'file' => '/app/thing.php',
                    'line' => 1,
                ],
            ],
        ];

        $this->assertSame(
            json_encode($expected, JSON_PRETTY_PRINT),
            (new JsonReporter())->generate($summary)
        );
    }
}
