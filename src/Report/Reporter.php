<?php

namespace Ntzm\PhpUcf\Report;

interface Reporter
{
    public function generate(Summary $summary): string;
}
