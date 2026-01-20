<?php

declare(strict_types=1);

namespace PeterFox\PhpUnitToonResultPrinter;

use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\FailedSubscriber;

final class TestFailedSubscriber implements FailedSubscriber
{
    public function __construct(private TestResultCollector $collector)
    {
    }

    public function notify(Failed $event): void
    {
        $this->collector->addFailed($event);
    }
}
