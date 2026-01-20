<?php

declare(strict_types=1);

namespace PeterFox\PhpUnitToonResultPrinter\Tests;

use PHPUnit\Framework\TestCase;

class ExtensionTest extends TestCase
{
    public function test_it_outputs_toon_format(): void
    {
        $command = sprintf(
            '%s vendor/bin/phpunit tests/ExampleTest.php --configuration phpunit.xml.dist',
            PHP_BINARY
        );

        $output = [];
        exec($command, $output);

        $outputString = implode("\n", $output);

        $this->assertStringContainsString('test: "PeterFox\\\\PhpUnitToonResultPrinter\\\\Tests\\\\ExampleTest::test_it_passes"', $outputString);
        $this->assertStringContainsString('status: passed', $outputString);
        $this->assertStringContainsString('test: "PeterFox\\\\PhpUnitToonResultPrinter\\\\Tests\\\\ExampleTest::test_it_fails"', $outputString);
        $this->assertStringContainsString('status: failed', $outputString);
        $this->assertStringContainsString('message: Failed asserting that false is true.', $outputString);
    }
}
