<?php

namespace Ntzm\UselessCommentFinder;

use Ntzm\UselessCommentFinder\Comment\Comment;

final class Classifier
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function isUseless(Comment $comment): bool
    {
        if ($comment->isType(Comment::TYPE_DOC)) {
            return false;
        }

        $content = $comment->getContent();

        if ($content === '') {
            return false;
        }

        if ($this->isNote($content)) {
            return false;
        }

        if (str_word_count($content) < $this->config->getMinimumCommentWordCount()) {
            return true;
        }

        return false;
    }

    private function isNote(string $comment): bool
    {
        foreach ($this->config->getNotePrefixes() as $prefix) {
            $position = strpos($comment, $prefix);

            if ($position === 0 || $position === 1) {
                return true;
            }
        }

        return false;
    }
}
