<?php

namespace Ntzm\Tests\PhpUcf\Comment;

use Ntzm\PhpUcf\Comment\Comment;
use Ntzm\PhpUcf\Report\Summary;
use Ntzm\PhpUcf\Report\TextReporter;
use Ntzm\PhpUcf\Violation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\SplFileInfo;

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

        $summary = new Summary($violations, 100, 3145728);

        $expected =
            'Potentially useless comment found in thing.php on line 1'.PHP_EOL.
            ''.PHP_EOL.
            'Time: 0.1 seconds'.PHP_EOL.
            'Memory: 3 MB'.PHP_EOL;

        $this->assertSame($expected, (new TextReporter())->generate($summary));
    }
}
