<?php

namespace Ntzm\PhpUcf\Classifier;

use Ntzm\PhpUcf\Comment\Comment;

interface Classifier
{
    public function isUseless(Comment $comment): ?bool;
}
