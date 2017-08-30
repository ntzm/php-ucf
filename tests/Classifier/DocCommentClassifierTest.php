<?php

namespace Ntzm\Tests\PhpUcf\Classifier;

use Ntzm\PhpUcf\Classifier\DocCommentClassifier;
use Ntzm\PhpUcf\Comment\Comment;
use PHPUnit\Framework\TestCase;

final class DocCommentClassifierTest extends TestCase
{
    public function testClassifier(): void
    {
        $comment = new Comment('', 1, Comment::TYPE_DOC);
        $this->assertFalse((new DocCommentClassifier())->isUseless($comment));

        $comment = new Comment('', 1, Comment::TYPE_SINGLE_LINE);
        $this->assertNull((new DocCommentClassifier())->isUseless($comment));

        $comment = new Comment('', 1, Comment::TYPE_MULTI_LINE);
        $this->assertNull((new DocCommentClassifier())->isUseless($comment));
    }
}
