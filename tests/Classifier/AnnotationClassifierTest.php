<?php

namespace Ntzm\Tests\PhpUcf\Classifier;

use Ntzm\PhpUcf\Classifier\AnnotationClassifier;
use Ntzm\PhpUcf\Comment\Comment;
use PHPUnit\Framework\TestCase;

final class AnnotationClassifierTest extends TestCase
{
    public function testClassifier(): void
    {
        $comment = new Comment('@ignoreThing', 1, Comment::TYPE_SINGLE_LINE);
        $this->assertFalse((new AnnotationClassifier())->isUseless($comment));

        $comment = new Comment('foo bar', 1, Comment::TYPE_SINGLE_LINE);
        $this->assertNull((new AnnotationClassifier())->isUseless($comment));

        $comment = new Comment('foo bar', 1, Comment::TYPE_SINGLE_LINE);
        $this->assertNull((new AnnotationClassifier())->isUseless($comment));
    }
}
