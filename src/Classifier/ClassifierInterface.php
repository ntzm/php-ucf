<?php

namespace Ntzm\PhpUcf\Classifier;

use Ntzm\PhpUcf\Comment\Comment;

interface ClassifierInterface
{
    public function isUseless(Comment $comment): ?bool;
}
