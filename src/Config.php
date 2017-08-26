<?php

namespace Ntzm\UselessCommentFinder;

final class Config
{
    public function getMinimumCommentWordCount(): int
    {
        return 3;
    }

    public function getNotePrefixes(): array
    {
        return [
            'NOTE',
            'OPTIMIZE',
            'OPTIMISE',
            'TODO',
            'HACK',
            'FIXME',
            'BUG',
            'XXX',
        ];
    }
}
