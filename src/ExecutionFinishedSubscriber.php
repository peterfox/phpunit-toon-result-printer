<?php

declare(strict_types=1);

namespace PeterFox\PhpUnitToonResultPrinter;

use PHPUnit\Event\TestRunner\ExecutionFinished;
use PHPUnit\Event\TestRunner\ExecutionFinishedSubscriber as PHPUnitExecutionFinishedSubscriber;

final class ExecutionFinishedSubscriber implements PHPUnitExecutionFinishedSubscriber
{
    public function __construct(private TestResultCollector $collector)
    {
    }

    public function notify(ExecutionFinished $event): void
    {
        $this->collector->printResults();
    }
}
