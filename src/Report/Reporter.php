<?php

namespace Ntzm\UselessCommentFinder\Report;

interface Reporter
{
    public function generate(Summary $summary): string;
}
