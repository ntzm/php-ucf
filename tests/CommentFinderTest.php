<?php

namespace Ntzm\Tests\UselessCommentFinder;

use Ntzm\UselessCommentFinder\Comment\Finder;
use PHPUnit\Framework\TestCase;

final class CommentFinderTest extends TestCase
{
    /**
     * @param array  $expected
     * @param string $input
     *
     * @dataProvider provideCommentedCode
     */
    public function testFindComments(array $expected, string $input): void
    {
        $finder = new Finder();

        $this->assertSame($expected, array_column($finder->find($input), 1));
    }

    public function provideCommentedCode(): array
    {
        return [
            [
                [
                    "// This is a comment\n",
                    '/* This is another comment */',
                ],
                '
                <?php
                
                // This is a comment
                echo "foo // bar";
                
                echo \'foo // bar\';
                /* This is another comment */
                ',
            ],
        ];
    }
}
