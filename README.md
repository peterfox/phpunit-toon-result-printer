# PHPUnit TOON Result Printer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/peterfox/phpunit-toon-result-printer.svg?style=flat-square)](https://packagist.org/packages/peterfox/phpunit-toon-result-printer)
[![Tests](https://img.shields.io/github/actions/workflow/status/peterfox/phpunit-toon-result-printer/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/peterfox/phpunit-toon-result-printer/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/peterfox/phpunit-toon-result-printer.svg?style=flat-square)](https://packagist.org/packages/peterfox/phpunit-toon-result-printer)

A PHPUnit results printer that outputs test results in [TOON](https://github.com/helgesverre/toon) (Token-Oriented Object Notation) format.

This extension is specifically designed to provide compact, highly readable test results optimized for LLM (Large Language Model) consumption, helping AI agents quickly diagnose test failures.

## Features

- **Compact Output**: Uses TOON format to minimize token usage while remaining human-readable.
- **AI-Friendly Diagnostics**: Includes file paths, line numbers, error messages, and stack traces for failed and errored tests.
- **Concise Success Reports**: Passed tests only show the test name and status to keep the output focused on what needs attention.
- **PHPUnit Extension**: Integrates seamlessly as a PHPUnit extension.

## Installation

You can install the package via composer:

```bash
composer require peterfox/phpunit-toon-result-printer
```

## Configuration

### Permanent (Recommended)

To use this printer, add it as an extension in your `phpunit.xml` or `phpunit.xml.dist` file:

```xml
<extensions>
    <bootstrap class="PeterFox\PhpUnitToonResultPrinter\ToonResultPrinterExtension" />
</extensions>
```

When enabled, it will suppress PHPUnit's default progress and result output, replacing it with TOON-formatted output at the end of the test execution.

### Manual (CLI)

If you don't want to enable it for all test runs, you can trigger it manually using the `--extension` flag:

```bash
vendor/bin/phpunit --extension "PeterFox\PhpUnitToonResultPrinter\ToonResultPrinterExtension"
```

## Example Output

For a passing test:
```text
test: "Tests\\ExampleTest::test_it_passes"
status: passed
```

For a failing test:
```text
test: "Tests\\ExampleTest::test_it_fails"
status: failed
file: "tests/ExampleTest.php"
line: 16
message: "Failed asserting that false is true."
description: "Failed asserting that false is true."
stackTrace: "..."
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Peter Fox](https://github.com/peterfox)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
