<?php

declare(strict_types=1);

namespace Tests\Unit;

use Elephant\Duration\Duration;
use Testo\Assert;
use Testo\Data\DataSet;
use Testo\Test;

final class DurationTest
{
    #[Test]
    public function fromSecondsCreatesCorrectDuration(): void
    {
        $d = Duration::fromSeconds(3661);
        Assert::same($d->toTotalSeconds(), 3661);
    }

    #[Test]
    public function fromSecondsNormalizesNegativeToZero(): void
    {
        $d = Duration::fromSeconds(-100);
        Assert::true($d->isZero());
        Assert::same($d->toTotalSeconds(), 0);
    }

    #[Test]
    public function fromSecondsZeroIsZero(): void
    {
        Assert::true(Duration::fromSeconds(0)->isZero());
    }

    #[Test]
    #[DataSet([0,     0, 0, 0],  '0 seconds')]
    #[DataSet([59,    0, 0, 59], '59 seconds')]
    #[DataSet([60,    0, 1, 0],  '1 minute')]
    #[DataSet([3599,  0, 59, 59],'59 minutes')]
    #[DataSet([3600,  1, 0, 0],  '1 hour')]
    #[DataSet([3661,  1, 1, 1],  '1h 1m 1s')]
    #[DataSet([90061, 25, 1, 1], '> 24 hours')]
    public function partsAreCorrect(int $total, int $h, int $m, int $s): void
    {
        $d = Duration::fromSeconds($total);
        Assert::same($d->hours(),   $h);
        Assert::same($d->minutes(), $m);
        Assert::same($d->seconds(), $s);
    }

    #[Test]
    public function toTotalMinutesRoundsDown(): void
    {
        $d = Duration::fromSeconds(3661); // 61m 1s
        Assert::same($d->toTotalMinutes(), 61);
    }

    #[Test]
    public function toTotalHoursRoundsDown(): void
    {
        $d = Duration::fromSeconds(7200 + 3599); // 2h 59m 59s
        Assert::same($d->toTotalHours(), 2);
    }

    #[Test]
    public function hoursAndToTotalHoursAreEquivalent(): void
    {
        $d = Duration::fromSeconds(90061);
        Assert::same($d->hours(), $d->toTotalHours());
    }

    #[Test]
    public function partsReturnsCorrectArray(): void
    {
        $d = Duration::fromSeconds(3661);

        Assert::array($d->parts())
              ->hasKeys('hours', 'minutes', 'seconds');

        Assert::same($d->parts(), ['hours' => 1, 'minutes' => 1, 'seconds' => 1]);
    }

    #[Test]
    #[DataSet([0,     '00:00:00'])]
    #[DataSet([3661,  '01:01:01'])]
    #[DataSet([90061, '25:01:01'], '> 24 hours')]
    #[DataSet([59,    '00:00:59'])]
    public function toHmsFormatsCorrectly(int $seconds, string $expected): void
    {
        Assert::same(Duration::fromSeconds($seconds)->toHms(), $expected);
    }

    #[Test]
    public function formatUsesProvidedLabels(): void
    {
        $d = Duration::fromSeconds(3661);
        $result = $d->format('h', 'min', 'sec');

        Assert::string($result)
              ->contains('1 h')
              ->contains('1 min')
              ->contains('1 sec');
    }

    #[Test]
    public function betweenCalculatesDifference(): void
    {
        $start = new \DateTimeImmutable('2024-01-01 10:00:00');
        $end   = new \DateTimeImmutable('2024-01-01 11:01:01');

        $d = Duration::between($start, $end);
        Assert::same($d->toTotalSeconds(), 3661);
    }

    #[Test]
    public function betweenWithNegativeDiffNormalizesToZero(): void
    {
        $start = new \DateTimeImmutable('2024-01-01 12:00:00');
        $end   = new \DateTimeImmutable('2024-01-01 10:00:00');

        Assert::true(Duration::between($start, $end)->isZero());
    }

    #[Test]
    public function sinceReturnsPositiveDuration(): void
    {
        $past = new \DateTimeImmutable('-1 hour');
        $d = Duration::since($past);

        Assert::int($d->toTotalSeconds())->greaterThan(0);
    }
}