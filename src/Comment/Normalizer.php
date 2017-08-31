<?php

namespace Ntzm\PhpUcf\Comment;

final class Normalizer
{
    private $typeDetector;

    public function __construct()
    {
        $this->typeDetector = new TypeDetector();
    }

    public function normalize(string $comment): string
    {
        switch ($this->typeDetector->detect($comment)) {
            case Comment::TYPE_SINGLE_LINE:
                return trim(substr($comment, 2));
            case Comment::TYPE_MULTI_LINE:
                return trim(substr($comment, 2, -2));
            case Comment::TYPE_DOC:
                return $this->normalizeDocComment($comment);
        }

        throw new InvalidCommentException(); // @codeCoverageIgnore
    }

    private function normalizeDocComment(string $comment): string
    {
        $comment = trim(substr($comment, 3, -2));
        $lines = explode("\n", $comment);

        return implode("\n", array_map(function (string $line) {
            $line = trim($line);

            if (strpos($line, '*') !== 0) {
                return $line;
            }

            return trim(substr($line, 1));
        }, $lines));
    }
}
