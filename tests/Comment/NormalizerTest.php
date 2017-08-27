<?php

namespace Ntzm\Tests\UselessCommentFinder\Comment;

use Ntzm\UselessCommentFinder\Comment\Normalizer;
use Ntzm\UselessCommentFinder\Comment\InvalidCommentException;
use PHPUnit\Framework\TestCase;

final class NormalizerTest extends TestCase
{
    /**
     * @param string $expected
     * @param string $input
     *
     * @dataProvider provideSingleLineComments
     */
    public function testSingleLineComment(string $expected, string $input): void
    {
        $normalizer = new Normalizer();

        $this->assertSame($expected, $normalizer->normalize($input));
    }

    public function provideSingleLineComments(): array
    {
        return [
            ['foo', '//foo'],
            ['foo', '// foo'],
            ['foo', '//     foo     '],
        ];
    }

    /**
     * @param string $expected
     * @param string $input
     *
     * @dataProvider provideMultiLineComments
     */
    public function testMultiLineComment(string $expected, string $input): void
    {
        $normalizer = new Normalizer();

        $this->assertSame($expected, $normalizer->normalize($input));
    }

    public function provideMultiLineComments(): array
    {
        return [
            ['foo', '/*foo*/'],
            ['foo', '/* foo */'],
            ['foo', '/*     foo      */'],
        ];
    }

    /**
     * @param string $expected
     * @param string $input
     *
     * @dataProvider provideDocComments
     */
    public function testDocComment(string $expected, string $input): void
    {
        $normalizer = new Normalizer();

        $this->assertSame($expected, $normalizer->normalize($input));
    }

    public function provideDocComments(): array
    {
        return [
            ['foo', '/**foo*/'],
            ['foo', '/** foo */'],
            ["foo\nbar\nbaz", "/**\n * foo\n * bar\n * baz\n */"],
            ["foo\nbar\nbaz", "/**foo\n * bar\n * baz\n */"],
            ["foo\nbar\nbaz", "/** foo  \n   *   bar  \n   *  baz   \n */"],
        ];
    }

    public function testInvalidComment(): void
    {
        $this->expectException(InvalidCommentException::class);

        $normalizer = new Normalizer();

        $normalizer->normalize('not a comment');
    }
}
