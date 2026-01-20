<?php

declare(strict_types=1);

namespace PeterFox\PhpUnitToonResultPrinter\Tests;

use PeterFox\PhpUnitToonResultPrinter\ExecutionFinishedSubscriber;
use PeterFox\PhpUnitToonResultPrinter\TestErroredSubscriber;
use PeterFox\PhpUnitToonResultPrinter\TestFailedSubscriber;
use PeterFox\PhpUnitToonResultPrinter\TestPassedSubscriber;
use PeterFox\PhpUnitToonResultPrinter\TestResultCollector;
use PHPUnit\Event\Code\Test;
use PHPUnit\Event\Code\Throwable;
use PHPUnit\Event\Telemetry\GarbageCollectorStatus;
use PHPUnit\Event\Telemetry\Info;
use PHPUnit\Event\Telemetry\Snapshot;
use PHPUnit\Event\Telemetry\Duration;
use PHPUnit\Event\Telemetry\HRTime;
use PHPUnit\Event\Telemetry\MemoryUsage;
use PHPUnit\Event\Test\Errored;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\Passed;
use PHPUnit\Event\TestRunner\ExecutionFinished;
use PHPUnit\Framework\TestCase;

class SubscriberTest extends TestCase
{
    public function test_passed_subscriber_notifies_collector(): void
    {
        $collector = $this->createMock(TestResultCollector::class);
        $test = $this->createMock(Test::class);
        $event = new Passed($this->createTelemetryInfo(), $test);

        $collector->expects($this->once())
            ->method('addPassed')
            ->with($event);

        $subscriber = new TestPassedSubscriber($collector);
        $subscriber->notify($event);
    }

    public function test_failed_subscriber_notifies_collector(): void
    {
        $collector = $this->createMock(TestResultCollector::class);
        $test = $this->createMock(Test::class);
        $throwable = new Throwable('Error', 'msg', 'desc', 'stack', null);
        $event = new Failed($this->createTelemetryInfo(), $test, $throwable, null);

        $collector->expects($this->once())
            ->method('addFailed')
            ->with($event);

        $subscriber = new TestFailedSubscriber($collector);
        $subscriber->notify($event);
    }

    public function test_errored_subscriber_notifies_collector(): void
    {
        $collector = $this->createMock(TestResultCollector::class);
        $test = $this->createMock(Test::class);
        $throwable = new Throwable('Error', 'msg', 'desc', 'stack', null);
        $event = new Errored($this->createTelemetryInfo(), $test, $throwable);

        $collector->expects($this->once())
            ->method('addErrored')
            ->with($event);

        $subscriber = new TestErroredSubscriber($collector);
        $subscriber->notify($event);
    }

    public function test_execution_finished_subscriber_triggers_printing(): void
    {
        $collector = $this->createMock(TestResultCollector::class);
        $event = new ExecutionFinished($this->createTelemetryInfo());

        $collector->expects($this->once())
            ->method('printResults');

        $subscriber = new ExecutionFinishedSubscriber($collector);
        $subscriber->notify($event);
    }

    private function createTelemetryInfo(): Info
    {
        return new Info(
            new Snapshot(
                HRTime::fromSecondsAndNanoseconds(1, 0),
                MemoryUsage::fromBytes(1000),
                MemoryUsage::fromBytes(1000),
                new GarbageCollectorStatus(0, 0, 0, 0, 0.0, 0.0, 0.0, 0.0, false, false, false, 0),
            ),
            Duration::fromSecondsAndNanoseconds(0, 100),
            MemoryUsage::fromBytes(0),
            Duration::fromSecondsAndNanoseconds(0, 100),
            MemoryUsage::fromBytes(0),
        );
    }
}
