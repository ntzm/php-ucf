<?php

namespace Ntzm\Tests\PhpUcf\Classifier;

use Ntzm\PhpUcf\Classifier\EmptyCommentClassifier;
use Ntzm\PhpUcf\Comment\Comment;
use PHPUnit\Framework\TestCase;

final class EmptyCommentClassifierTest extends TestCase
{
    public function testClassifier(): void
    {
        $comment = new Comment('', 1, Comment::TYPE_SINGLE_LINE);
        $this->assertFalse((new EmptyCommentClassifier())->isUseless($comment));

        $comment = new Comment('hello', 1, Comment::TYPE_SINGLE_LINE);
        $this->assertNull((new EmptyCommentClassifier())->isUseless($comment));
    }
}
