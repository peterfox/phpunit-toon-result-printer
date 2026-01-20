<?php

declare(strict_types=1);

namespace PeterFox\PhpUnitToonResultPrinter;

use HelgeSverre\Toon\Toon;
use PHPUnit\Event\Test\Errored;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\Passed;

final class TestResultCollector
{
    /** @var array<array{test: string, status: string, message?: string}> */
    private array $results = [];

    public function addPassed(Passed $event): void
    {
        $this->results[] = [
            'test' => $event->test()->id(),
            'status' => 'passed',
        ];
    }

    public function addFailed(Failed $event): void
    {
        $this->results[] = [
            'test' => $event->test()->id(),
            'status' => 'failed',
            'message' => $event->throwable()->message(),
        ];
    }

    public function addErrored(Errored $event): void
    {
        $this->results[] = [
            'test' => $event->test()->id(),
            'status' => 'errored',
            'message' => $event->throwable()->message(),
        ];
    }

    public function printResults(): void
    {
        echo Toon::encode($this->results) . PHP_EOL;
    }
}
