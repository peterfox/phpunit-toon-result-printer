<?php

declare(strict_types=1);

namespace PeterFox\PhpUnitToonResultPrinter;

use HelgeSverre\Toon\Toon;
use PHPUnit\Event\Code\Test;
use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Code\Throwable;
use PHPUnit\Event\Test\Errored;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\Passed;

class TestResultCollector
{
    /** @var array<array{test: string, status: string, file: string, line?: int, message?: string, description?: string, stackTrace?: string}> */
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
        $this->results[] = array_merge(
            [
                'test' => $event->test()->id(),
                'status' => 'failed',
            ],
            $this->getTestData($event->test()),
            $this->getThrowableData($event->throwable())
        );
    }

    public function addErrored(Errored $event): void
    {
        $this->results[] = array_merge(
            [
                'test' => $event->test()->id(),
                'status' => 'errored',
            ],
            $this->getTestData($event->test()),
            $this->getThrowableData($event->throwable())
        );
    }

    public function printResults(): void
    {
        $hasErrors = array_reduce(
            $this->results,
            fn (bool $carry, array $result) => $carry || in_array($result['status'], ['failed', 'errored'], true),
            false
        );

        if (! $hasErrors) {
            echo 'ok' . PHP_EOL;

            return;
        }

        echo Toon::encode($this->results) . PHP_EOL;
    }

    /**
     * @return array{file: string, line?: int}
     */
    private function getTestData(Test $test): array
    {
        $data = [
            'file' => $test->file(),
        ];

        if ($test instanceof TestMethod) {
            $data['line'] = $test->line();
        }

        return $data;
    }

    /**
     * @return array{message: string, description: string, stackTrace: string}
     */
    private function getThrowableData(Throwable $throwable): array
    {
        return [
            'message' => $throwable->message(),
            'description' => $throwable->description(),
            'stackTrace' => $throwable->stackTrace(),
        ];
    }
}
