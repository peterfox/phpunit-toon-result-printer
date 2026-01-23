<?php

declare(strict_types=1);

namespace PeterFox\PhpUnitToonResultPrinter\Tests;

use PeterFox\PhpUnitToonResultPrinter\TestResultCollector;
use PHPUnit\Event\Code\TestDox;
use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Code\Throwable;
use PHPUnit\Event\Telemetry\Duration;
use PHPUnit\Event\Telemetry\GarbageCollectorStatus;
use PHPUnit\Event\Telemetry\HRTime;
use PHPUnit\Event\Telemetry\Info;
use PHPUnit\Event\Telemetry\MemoryUsage;
use PHPUnit\Event\Telemetry\Snapshot;
use PHPUnit\Event\Test\Errored;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\Passed;
use PHPUnit\Event\TestData\TestDataCollection;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\MetadataCollection;

class TestResultCollectorTest extends TestCase
{
    private TestResultCollector $collector;

    protected function setUp(): void
    {
        $this->collector = new TestResultCollector();
    }

    public function test_it_collects_passed_tests(): void
    {
        $test = new TestMethod(
            'TestClass',
            'testMethod',
            'testFile.php',
            10,
            new TestDox('TestClass', 'testMethod', 'testMethod'),
            MetadataCollection::fromArray([]),
            TestDataCollection::fromArray([])
        );

        $telemetryInfo = $this->createTelemetryInfo();

        $event = new Passed($telemetryInfo, $test);

        $this->collector->addPassed($event);
        $this->expectOutputString('ok' . PHP_EOL);
        $this->collector->printResults();
    }

    public function test_it_collects_failed_tests(): void
    {
        $test = new TestMethod(
            'TestClass',
            'testFailed',
            'testFile.php',
            20,
            new TestDox('TestClass', 'testFailed', 'testFailed'),
            MetadataCollection::fromArray([]),
            TestDataCollection::fromArray([])
        );

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

        $this->expectOutputRegex('/"TestClass::testFailed",failed,testFile\.php,20,Failed asserting that false is true\.,description,stack trace/');
        $this->collector->printResults();
    }

    public function test_it_collects_errored_tests(): void
    {
        $test = new TestMethod(
            'TestClass',
            'testError',
            'testFile.php',
            30,
            new TestDox('TestClass', 'testError', 'testError'),
            MetadataCollection::fromArray([]),
            TestDataCollection::fromArray([])
        );

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

        $this->expectOutputRegex('/"TestClass::testError",errored,testFile\.php,30,Something went wrong\.,description,stack trace/');
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
