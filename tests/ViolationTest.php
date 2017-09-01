<?php

namespace Ntzm\Tests\PhpUcf;

use Ntzm\PhpUcf\Comment\Comment;
use Ntzm\PhpUcf\Violation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\SplFileInfo;

final class ViolationTest extends TestCase
{
    public function testViolation(): void
    {
        $comment = new Comment('', 1, Comment::TYPE_SINGLE_LINE);
        $file = $this->createMock(SplFileInfo::class);

        $violation = new Violation($comment, $file);

        $this->assertSame($comment, $violation->getComment());
        $this->assertSame($file, $violation->getFile());
    }
}
