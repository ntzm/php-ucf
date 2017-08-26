<?php

namespace Ntzm\Tests\UselessCommentFinder;

use Ntzm\UselessCommentFinder\Classifier;
use Ntzm\UselessCommentFinder\Comment\Comment;
use Ntzm\UselessCommentFinder\Config;
use PHPUnit\Framework\TestCase;

final class ClassifierTest extends TestCase
{
    public function testEmptyCommentsAreUseful(): void
    {
        $classifier = new Classifier(new Config());

        $this->assertFalse($classifier->isUseless(new Comment('', 1, Comment::TYPE_SINGLE_LINE)));
    }

    /**
     * @param string $comment
     *
     * @dataProvider provideNoteComments
     */
    public function testNotesAreUseful(string $comment): void
    {
        $classifier = new Classifier(new Config());

        $this->assertFalse($classifier->isUseless(new Comment($comment, 1, Comment::TYPE_SINGLE_LINE)));
    }

    public function provideNoteComments(): array
    {
        return [
            ['NOTE: This does not work yet'],
            ['HACK: This is terrible and should never have been committed'],
            ['FIXME: Fix before Black Friday!'],
            ['HACK'],
            ['XXX: Broken as of 2017-01-03'],
            ['TODO: Save responses to the database'],
            ['BUG: Breaks on PHP 5.5'],
        ];
    }

    /**
     * @param string $comment
     *
     * @dataProvider provideShortComments
     */
    public function testShortCommentsAreNotUseful(string $comment): void
    {
        $classifier = new Classifier(new Config());

        $this->assertTrue($classifier->isUseless(new Comment($comment, 1, Comment::TYPE_SINGLE_LINE)));
    }

    public function provideShortComments(): array
    {
        return [
            ['If active'],
            ['Loop start'],
            ['Loop end'],
        ];
    }
}
