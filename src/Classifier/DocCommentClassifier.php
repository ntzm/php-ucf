<?php

namespace Ntzm\PhpUcf\Classifier;

use Ntzm\PhpUcf\Comment\Comment;

final class DocCommentClassifier implements ClassifierInterface
{
    public function isUseless(Comment $comment): ?bool
    {
        if ($comment->isType(Comment::TYPE_DOC)) {
            return false;
        }

        return null;
    }
}
