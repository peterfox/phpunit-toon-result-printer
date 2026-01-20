<?php

namespace PeterFox\PhpUnitToonResultPrinter\Tests;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_it_passes(): void
    {
        $this->assertTrue(true);
    }

    public function test_it_fails(): void
    {
        $this->assertTrue(false);
    }
}
