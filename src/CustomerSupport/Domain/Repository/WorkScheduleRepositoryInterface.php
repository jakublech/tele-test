<?php

namespace App\CustomerSupport\Domain\Repository;

use DateTimeInterface;

interface WorkScheduleRepositoryInterface
{
    public function schedule(int $employeeId, DateTimeInterface $dateStart, DateTimeInterface $dateEnd): void;
    public function hasScheduledWorkAt(int $employeeId, DateTimeInterface $date): bool;
    public function getHoursScheduledOnWeek(int $employeeId, DateTimeInterface $date): int;
    public function getHoursScheduledOnMonth(int $employeeId, DateTimeInterface $date): int;

}