<?php

namespace Ntzm\PhpUcf\Classifier;

use Ntzm\PhpUcf\Comment\Comment;

final class ShortCommentClassifier implements Classifier
{
    private const MINIMUM_COMMENT_WORD_COUNT = 3;

    public function isUseless(Comment $comment): ?bool
    {
        if (str_word_count($comment->getContent()) < self::MINIMUM_COMMENT_WORD_COUNT) {
            return true;
        }

        return null;
    }
}
