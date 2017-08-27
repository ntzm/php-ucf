<?php

namespace Ntzm\UselessCommentFinder\Classifier;

use Ntzm\UselessCommentFinder\Comment\Comment;

final class DocCommentClassifier implements Classifier
{
    public function isUseless(Comment $comment): ?bool
    {
        if ($comment->isType(Comment::TYPE_DOC)) {
            return false;
        }

        return null;
    }
}
