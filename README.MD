<p align="center">
    <a href="https://github.com/elephant-php/duration" target="_blank">
        <img src="elephant-php.webp" height="256" alt="elephant-php">
    </a>
    <h1 align="center">Duration</h1>
    <p align="center">A small immutable value object representing elapsed time.</p>
    <p align="center">
        <a href="https://packagist.org/packages/elephant-php/duration"><img src="https://img.shields.io/packagist/v/elephant-php/duration.svg" alt="Latest Version"></a>
        <a href="./LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
        <a href="https://www.php.net/releases/8.1/en.php"><img src="https://img.shields.io/badge/PHP-8.1%2B-777bb4.svg" alt="PHP 8.1+"></a>
    </p>
    <br>
</p>

## Use cases

- Measuring execution time
- Logging
- Reporting
- Working with time differences

---

## Installation

```bash
composer require elephant-php/duration
```

## Creation

from dates
```php
use ElephantPhp\Duration\Duration;

$start    = new DateTimeImmutable('2026-01-01 10:00:00');
$end      = new DateTimeImmutable('2026-01-01 11:01:01');

$duration = Duration::between($start, $end);
```
from seconds
```php
$duration = Duration::fromSeconds(3661);
```
from since to now
```php
$duration = Duration::since($startedAt);
```

## Access values
```php
$duration->hours();
$duration->minutes();
$duration->seconds();
```

> ⚠️ **Note**
> `minutes()` and `seconds()` return remaining parts (0–59), not total values.

## Total values
```php
$duration->toTotalSeconds();
$duration->toTotalMinutes();
$duration->toTotalHours();
```

## Formatting
```php
// arguments are labels
$duration->format('h', 'm', 's'); // "1 h, 1 m, 1 s"

$duration->toHms();              // "01:01:01"
```

## Example
```php
$duration = Duration::fromSeconds(3661);

echo $duration->format('h', 'm', 's'); // 1 h, 1 m, 1 s
echo $duration->toHms();               // 01:01:01

echo $duration->seconds();             // 1
echo $duration->minutes();             // 1
echo $duration->hours();               // 1

echo $duration->toTotalSeconds();      // 3661
echo $duration->toTotalMinutes();      // 61
echo $duration->toTotalHours();        // 1
```

## License
MIT License

Please see [`LICENSE`](./LICENSE.md) for more information.