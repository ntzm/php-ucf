<?php

namespace Ntzm\Tests\UselessCommentFinder\Classifier;

use Ntzm\UselessCommentFinder\Classifier\NoteClassifier;
use Ntzm\UselessCommentFinder\Comment\Comment;
use PHPUnit\Framework\TestCase;

final class NoteClassifierTest extends TestCase
{
    /**
     * @param bool|null $expected
     * @param string    $comment
     *
     * @dataProvider provideTestCases
     */
    public function testClassifier(?bool $expected, string $comment): void
    {
        $comment = new Comment($comment, 1, Comment::TYPE_SINGLE_LINE);

        $this->assertSame($expected, (new NoteClassifier())->isUseless($comment));
    }

    public function provideTestCases(): array
    {
        return [
            [false, 'NOTE: Foo'],
            [false, 'OPTIMISE: Foo'],
            [false, 'OPTIMISE: Foo'],
            [false, 'TODO: Foo'],
            [false, 'HACK: Foo'],
            [false, 'FIXME: Foo'],
            [false, 'BUG: Foo'],
            [false, 'XXX: Foo'],
            [false, '@NOTE: Foo'],
            [false, '@OPTIMISE: Foo'],
            [false, '@OPTIMISE: Foo'],
            [false, '@TODO: Foo'],
            [false, '@HACK: Foo'],
            [false, '@FIXME: Foo'],
            [false, '@BUG: Foo'],
            [false, '@XXX: Foo'],
            [null, 'Please note that this...'],
            [null, 'A bug here'],
        ];
    }
}
