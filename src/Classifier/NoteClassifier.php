<?php

namespace Ntzm\UselessCommentFinder\Classifier;

use Ntzm\UselessCommentFinder\Comment\Comment;

final class NoteClassifier implements Classifier
{
    private const NOTE_PREFIXES = [
        'NOTE',
        'OPTIMIZE',
        'OPTIMISE',
        'TODO',
        'HACK',
        'FIXME',
        'BUG',
        'XXX',
    ];

    public function isUseless(Comment $comment): ?bool
    {
        $content = $comment->getContent();

        foreach (self::NOTE_PREFIXES as $prefix) {
            $position = strpos($content, $prefix);

            if ($position === 0 || $position === 1) {
                return false;
            }
        }

        return null;
    }
}
