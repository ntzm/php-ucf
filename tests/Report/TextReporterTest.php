<?php

namespace Ntzm\Tests\PhpUcf\Comment;

use Ntzm\PhpUcf\Comment\Comment;
use Ntzm\PhpUcf\Report\Summary;
use Ntzm\PhpUcf\Report\TextReporter;
use Ntzm\PhpUcf\Violation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Stopwatch\StopwatchEvent;

final class TextReporterTest extends TestCase
{
    public function testReporter()
    {
        $file = $this->createMock(SplFileInfo::class);
        $file->method('getPathname')->willReturn('thing.php');

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

        $expected =
            'Potentially useless comment found in thing.php on line 1'.PHP_EOL.
            ''.PHP_EOL.
            'Time: 0.1 seconds'.PHP_EOL.
            'Memory: 3 MB'.PHP_EOL;

        $this->assertSame($expected, (new TextReporter())->generate($summary));
    }
}
