<?php

namespace Ntzm\UselessCommentFinder\Classifier;

use Ntzm\UselessCommentFinder\Comment\Comment;

final class EmptyCommentClassifier implements Classifier
{
    public function isUseless(Comment $comment): ?bool
    {
        if ($comment->getContent() === '') {
            return false;
        }

        return null;
    }
}
