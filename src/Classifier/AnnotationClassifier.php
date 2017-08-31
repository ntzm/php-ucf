<?php

namespace Ntzm\PhpUcf\Classifier;

use Ntzm\PhpUcf\Comment\Comment;

final class AnnotationClassifier implements ClassifierInterface
{
    public function isUseless(Comment $comment): ?bool
    {
        if (strpos($comment->getContent(), '@') !== false) {
            return false;
        }

        return null;
    }
}
