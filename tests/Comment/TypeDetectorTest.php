<?php

namespace Ntzm\Tests\UselessCommentFinder\Comment;

use Ntzm\UselessCommentFinder\Comment\Comment;
use Ntzm\UselessCommentFinder\Comment\TypeDetector;
use PHPUnit\Framework\TestCase;

final class TypeDetectorTest extends TestCase
{
    /**
     * @param string $comment
     *
     * @dataProvider provideSingleLineComments
     */
    public function testSingleLine(string $comment): void
    {
        $detector = new TypeDetector();

        $this->assertSame(Comment::TYPE_SINGLE_LINE, $detector->detect($comment));
    }

    public function provideSingleLineComments(): array
    {
        return [
            ['//foo'],
            ['// foo'],
            ['//    foo'],
        ];
    }

    /**
     * @param string $comment
     *
     * @dataProvider provideMultiLineComments
     */
    public function testMultiLine(string $comment): void
    {
        $detector = new TypeDetector();

        $this->assertSame(Comment::TYPE_MULTI_LINE, $detector->detect($comment));
    }

    public function provideMultiLineComments(): array
    {
        return [
            ['/*foo*/'],
            ['/* foo */'],
            ['/*     foo    */'],
            ['/**/'],
        ];
    }

    /**
     * @param string $comment
     *
     * @dataProvider provideDocComments
     */
    public function testDoc(string $comment): void
    {
        $detector = new TypeDetector();

        $this->assertSame(Comment::TYPE_DOC, $detector->detect($comment));
    }

    public function provideDocComments(): array
    {
        return [
            ['/** foo*/'],
            ['/** foo */'],
            ['/**     foo    */'],
        ];
    }
}
