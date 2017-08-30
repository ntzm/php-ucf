<?php

namespace Ntzm\PhpUcf\Classifier;

use Ntzm\PhpUcf\Comment\Comment;

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
