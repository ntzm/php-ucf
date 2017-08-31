<?php

namespace Ntzm\Tests\PhpUcf\Comment;

use Ntzm\PhpUcf\Comment\Comment;
use PHPUnit\Framework\TestCase;

final class CommentTest extends TestCase
{
    public function testComment(): void
    {
        $comment = new Comment('hello', 20, Comment::TYPE_SINGLE_LINE);

        $this->assertSame('hello', $comment->getContent());
        $this->assertSame(20, $comment->getLine());
        $this->assertSame(Comment::TYPE_SINGLE_LINE, $comment->getType());
        $this->assertTrue($comment->isType(Comment::TYPE_SINGLE_LINE));
        $this->assertFalse($comment->isType(Comment::TYPE_MULTI_LINE));
    }
}
