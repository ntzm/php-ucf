<?php

namespace Ntzm\PhpUcf\Report;

interface ReporterInterface
{
    public function generate(Summary $summary): string;
}
