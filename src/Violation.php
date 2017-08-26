<?php

namespace Ntzm\UselessCommentFinder;

use Ntzm\UselessCommentFinder\Comment\Comment;
use Symfony\Component\Finder\SplFileInfo;

final class Violation
{
    private $comment;
    private $file;

    public function __construct(Comment $comment, SplFileInfo $file)
    {
        $this->comment = $comment;
        $this->file = $file;
    }

    public function getComment(): Comment
    {
        return $this->comment;
    }

    public function getFile(): SplFileInfo
    {
        return $this->file;
    }
}
