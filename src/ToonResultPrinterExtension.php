<?php

namespace PeterFox\PhpUnitToonResultPrinter;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

final class ToonResultPrinterExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $collector = new TestResultCollector();

        $facade->registerSubscriber(new TestPassedSubscriber($collector));
        $facade->registerSubscriber(new TestFailedSubscriber($collector));
        $facade->registerSubscriber(new TestErroredSubscriber($collector));
        $facade->registerSubscriber(new ExecutionFinishedSubscriber($collector));

        $facade->replaceProgressOutput();
        $facade->replaceResultOutput();
    }
}
