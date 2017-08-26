<?php

namespace Ntzm\UselessCommentFinder\Comment;

final class Comment
{
    public const TYPE_SINGLE_LINE = 1;
    public const TYPE_MULTI_LINE = 2;
    public const TYPE_DOC = 3;

    private $content;
    private $line;
    private $type;

    public function __construct(string $content, int $line, int $type)
    {
        $this->content = $content;
        $this->line = $line;
        $this->type = $type;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function isType(int $type): bool
    {
        return $this->type === $type;
    }
}
