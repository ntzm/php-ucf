<?php

namespace Ntzm\UselessCommentFinder\Report;

final class TextReporter implements Reporter
{
    public function generate(Summary $summary): string
    {
        $lines = [];

        /** @var \Ntzm\UselessCommentFinder\Violation $violation */
        foreach ($summary->getViolations() as $violation) {
            $lines[] = "Potentially useless comment found in {$violation->getFile()->getPathname()} on line {$violation->getComment()->getLine()}";
        }

        $lines[] = '';
        $lines[] = "Time: {$summary->getTimeInSeconds()} seconds";
        $lines[] = "Memory: {$summary->getMemoryInMegabytes()} MB";

        return implode(PHP_EOL, $lines);
    }
}
