<?php

namespace App\CustomerSupport\Domain\ValueObject;

use App\CustomerSupport\Domain\ValueObject\HoursRange;

final readonly class DateTimeAvailability
{
    public function __construct(
        public \DateTimeInterface $date,
        public HoursRange $hoursRange,
    ) {
    }

    public static function notAvailable(\DateTimeInterface $date): self
    {
        return new self($date, HoursRange::empty());
    }

    public function isAvailableBetween(\DateTimeInterface $startTime, \DateTimeInterface $endTime): bool
    {
        return $this->isAvailableAt($startTime) && $this->isAvailableAt($endTime);
    }

    public function isAvailableAt(\DateTime $dateTime): bool
    {
        return $this->date->format("Y-m-d") === $dateTime->format("Y-m-d")
            && $this->hoursRange->isDateInRange($dateTime);
    }
} 