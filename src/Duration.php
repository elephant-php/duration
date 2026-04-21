<?php

declare(strict_types=1);

namespace Elephant\Duration;

use DateTimeImmutable;
use DateTimeInterface;

final class Duration
{
    private const SECONDS_IN_MINUTE = 60;
    private const SECONDS_IN_HOUR   = 3600;

    private function __construct(
        private readonly int $totalSeconds
    ) {}

    /**
     * Create duration from seconds.
     */
    public static function fromSeconds(int $seconds): self
    {
        return new self(max(0, $seconds));
    }

    /**
     * Create duration between two dates.
     */
    public static function between(
        DateTimeInterface $start,
        DateTimeInterface $end
    ): self {
        return self::fromSeconds(
            $end->getTimestamp() - $start->getTimestamp()
        );
    }

    /**
     * Create duration from given date until now.
     */
    public static function since(DateTimeInterface $start): self
    {
        return self::between($start, new DateTimeImmutable());
    }

    /**
     * Total duration in Hours.
     */
    public function toTotalHours(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_IN_HOUR);
    }

    /**
     * Total duration in Minutes.
     */
    public function toTotalMinutes(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_IN_MINUTE);
    }

    /**
     * Total duration in seconds.
     */
    public function toTotalSeconds(): int
    {
        return $this->totalSeconds;
    }

    /**
     * Total hours (can be > 24)
     */
    public function hours(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_IN_HOUR);
    }

    /**
     * Minutes part (0–59)
     */
    public function minutes(): int
    {
        return intdiv($this->totalSeconds % self::SECONDS_IN_HOUR, self::SECONDS_IN_MINUTE);
    }

    /**
     * Seconds part (0–59)
     */
    public function seconds(): int
    {
        return $this->totalSeconds % self::SECONDS_IN_MINUTE;
    }

    /**
     * Returns parts as array.
     *
     * @return array{hours:int, minutes:int, seconds:int}
     */
    public function parts(): array
    {
        return [
            'hours'   => $this->hours(),
            'minutes' => $this->minutes(),
            'seconds' => $this->seconds(),
        ];
    }

    /**
     * Human-readable format.
     *
     * Example: "1 hour, 2 min, 3 sec"
     */
    public function format(string $h = '', string $m = '', string $s = ''): string
    {
        return sprintf(
            '%d %s, %d %s, %d %s',
            $this->hours(),   $h,
            $this->minutes(), $m,
            $this->seconds(), $s,
        );
    }

    /**
     * HH:MM:SS format.
     *
     * Example: "01:02:03"
     */
    public function toHms(): string
    {
        return sprintf(
            '%02d:%02d:%02d',
            $this->hours(),
            $this->minutes(),
            $this->seconds()
        );
    }

    /**
     * Check if duration is zero.
     */
    public function isZero(): bool
    {
        return $this->totalSeconds === 0;
    }
}