<?php

namespace Ntzm\Tests\PhpUcf\Comment;

use Ntzm\PhpUcf\Report\Summary;
use PHPUnit\Framework\TestCase;

final class SummaryTest extends TestCase
{
    public function testGetTimeInMilliseconds()
    {
        $summary = new Summary([], 100, 0);

        $this->assertEquals(100, $summary->getTimeInMilliseconds());
    }

    public function testGetTimeInSeconds()
    {
        $summary = new Summary([], 10000, 0);

        $this->assertEquals(10, $summary->getTimeInSeconds());
    }

    public function testGetMemoryInBytes()
    {
        $summary = new Summary([], 0, 100);

        $this->assertEquals(100, $summary->getMemoryInBytes());
    }

    public function testGetMemoryInMegabytes()
    {
        $summary = new Summary([], 0, 3145728);

        $this->assertEquals(3, $summary->getMemoryInMegabytes());
    }
}
