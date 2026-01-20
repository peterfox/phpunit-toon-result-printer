<?php

declare(strict_types=1);

namespace PeterFox\PhpUnitToonResultPrinter\Tests;

use PeterFox\PhpUnitToonResultPrinter\TestResultCollector;
use PHPUnit\Event\Code\Test;
use PHPUnit\Event\Code\Throwable;
use PHPUnit\Event\Telemetry\GarbageCollectorStatus;
use PHPUnit\Event\Telemetry\Info;
use PHPUnit\Event\Telemetry\Snapshot;
use PHPUnit\Event\Telemetry\Duration;
use PHPUnit\Event\Telemetry\HRTime;
use PHPUnit\Event\Telemetry\MemoryUsage;
use PHPUnit\Event\Test\Passed;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\Errored;
use PHPUnit\Framework\TestCase;

class TestResultCollectorTest extends TestCase
{
    private TestResultCollector $collector;

    protected function setUp(): void
    {
        $this->collector = new TestResultCollector();
    }

    public function test_it_collects_passed_tests(): void
    {
        $test = $this->createMock(Test::class);
        $test->method('id')->willReturn('TestClass::testMethod');

        $telemetryInfo = $this->createTelemetryInfo();

        $event = new Passed($telemetryInfo, $test);

        $this->collector->addPassed($event);

        $this->expectOutputRegex('/"TestClass::testMethod",passed/');
        $this->collector->printResults();
    }

    public function test_it_collects_failed_tests(): void
    {
        $test = $this->createMock(Test::class);
        $test->method('id')->willReturn('TestClass::testFailed');

        $throwable = new Throwable(
            'AssertionError',
            'Failed asserting that false is true.',
            'description',
            'stack trace',
            null
        );

        $telemetryInfo = $this->createTelemetryInfo();

        $event = new Failed($telemetryInfo, $test, $throwable, null);

        $this->collector->addFailed($event);

        $this->expectOutputRegex('/"TestClass::testFailed",failed,Failed asserting that false is true\./');
        $this->collector->printResults();
    }

    public function test_it_collects_errored_tests(): void
    {
        $test = $this->createMock(Test::class);
        $test->method('id')->willReturn('TestClass::testError');

        $throwable = new Throwable(
            'RuntimeException',
            'Something went wrong.',
            'description',
            'stack trace',
            null
        );

        $telemetryInfo = $this->createTelemetryInfo();

        $event = new Errored($telemetryInfo, $test, $throwable);

        $this->collector->addErrored($event);

        $this->expectOutputRegex('/"TestClass::testError",errored,Something went wrong\./');
        $this->collector->printResults();
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
