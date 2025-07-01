<?php

declare(strict_types=1);

namespace App\CustomerSupport\Domain\ValueObject;

use DateTimeInterface;

final readonly class HoursRange
{
    public function __construct(
        public int $hourFrom,
        public int $hourTo,
    ) {
        if ($hourFrom < 0 || $hourFrom > 24 || $hourTo < 0 || $hourTo > 24) {
            throw new \InvalidArgumentException('Invalid hour range. Must be between 0 and 24');
        }

        if ($hourFrom > $hourTo) {
            throw new \InvalidArgumentException('Invalid hour range. From hour must be less than to hour');
        }
    }

    public static function empty(): self
    {
        return new self(0,0);
    }

    public function hoursCount(): int
    {
        return $this->hourTo - $this->hourFrom;
    }

    public function isDateInRange(DateTimeInterface $datetime): bool
    {
        $hour = (int) $datetime->format("H");
        return $this->isHourInRange($hour);
    }

    public function isHourInRange(int $hour): bool
    {
        return $this->hourFrom <= $hour && $this->hourTo >= $hour;
    }
} 