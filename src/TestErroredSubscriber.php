<?php

declare(strict_types=1);

namespace PeterFox\PhpUnitToonResultPrinter;

use PHPUnit\Event\Test\Errored;
use PHPUnit\Event\Test\ErroredSubscriber;

final class TestErroredSubscriber implements ErroredSubscriber
{
    public function __construct(private TestResultCollector $collector)
    {
    }

    public function notify(Errored $event): void
    {
        $this->collector->addErrored($event);
    }
}
