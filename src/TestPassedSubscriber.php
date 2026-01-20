<?php

declare(strict_types=1);

namespace PeterFox\PhpUnitToonResultPrinter;

use PHPUnit\Event\Test\Passed;
use PHPUnit\Event\Test\PassedSubscriber;

final class TestPassedSubscriber implements PassedSubscriber
{
    public function __construct(private TestResultCollector $collector)
    {
    }

    public function notify(Passed $event): void
    {
        $this->collector->addPassed($event);
    }
}
