<?php

namespace Ntzm\UselessCommentFinder\Report;

final class JsonReporter implements Reporter
{
    public function generate(Summary $summary): string
    {
        $result = [
            'stats' => [
                'time' => $summary->getTimeInMilliseconds(),
                'memory' => $summary->getMemoryInBytes(),
            ],
            'violations' => [],
        ];

        /** @var \Ntzm\UselessCommentFinder\Violation $violation */
        foreach ($summary->getViolations() as $violation) {
            $result['violations'][] = [
                'file' => $violation->getFile()->getPathname(),
                'line' => $violation->getComment()->getLine(),
            ];
        }

        return json_encode($result, JSON_PRETTY_PRINT);
    }
}
