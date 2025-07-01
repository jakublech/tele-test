<?php

namespace App\CustomerSupport\Domain\Repository;

use App\CustomerSupport\Domain\ValueObject\DateTimeAvailability;
use DateTimeInterface;

interface EmployeeAvailabilityRepositoryInterface
{
    public function add(int $employeeId, DateTimeAvailability $availability): void;
    public function find(int $employeeId, DateTimeInterface $dateTime): ?DateTimeAvailability;
}