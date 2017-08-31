<?php

namespace Ntzm\Tests\PhpUcf\Classifier;

use Ntzm\PhpUcf\Classifier\ShortCommentClassifier;
use Ntzm\PhpUcf\Comment\Comment;
use PHPUnit\Framework\TestCase;

final class ShortCommentClassifierTest extends TestCase
{
    /**
     * @param string $comment
     *
     * @dataProvider provideShortComments
     */
    public function testShortComments(string $comment): void
    {
        $comment = new Comment($comment, 1, Comment::TYPE_SINGLE_LINE);

        $this->assertTrue((new ShortCommentClassifier())->isUseless($comment));
    }

    public function provideShortComments(): array
    {
        return [
            ['if thing'],
            ['foo bar'],
            [''],
            ['foo'],
        ];
    }

    /**
     * @param string $comment
     *
     * @dataProvider provideLongComments
     */
    public function testLongComments(string $comment): void
    {
        $comment = new Comment($comment, 1, Comment::TYPE_SINGLE_LINE);

        $this->assertNull((new ShortCommentClassifier())->isUseless($comment));
    }

    public function provideLongComments(): array
    {
        return [
            ['if thing is thingy'],
            ['foo bar baz'],
        ];
    }
}
