<p align="center">
    <a href="https://github.com/elephant-php/duration" target="_blank">
        <img src="elephant-php.webp" height="256" alt="elephant-php">
    </a>
    <h1 align="center">Duration</h1>
    <br>
</p>

A small immutable value object representing elapsed time.

## Use cases

- Measuring execution time
- Logging
- Reporting
- Working with time differences

---

## Creation

from dates
```php
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