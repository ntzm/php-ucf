<?php

namespace Ntzm\UselessCommentFinder\Comment;

final class Finder
{
    public function find(string $code): array
    {
        $tokens = token_get_all($code);
        $comments = [];

        foreach ($tokens as $token) {
            if ($token[0] === T_COMMENT || $token[0] === T_DOC_COMMENT) {
                $comments[] = $token;
            }
        }

        return $comments;
    }
}
