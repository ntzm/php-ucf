<?php

namespace Ntzm\UselessCommentFinder\Classifier;

use Ntzm\UselessCommentFinder\Comment\Comment;

interface Classifier
{
    public function isUseless(Comment $comment): ?bool;
}
