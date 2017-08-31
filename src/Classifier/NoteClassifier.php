<?php

namespace Ntzm\PhpUcf\Classifier;

use Ntzm\PhpUcf\Comment\Comment;

final class NoteClassifier implements ClassifierInterface
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
