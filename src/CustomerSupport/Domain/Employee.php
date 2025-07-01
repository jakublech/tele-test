<?php

declare(strict_types=1);

namespace App\CustomerSupport\Domain;

use App\CustomerSupport\Domain\Enum\QueueCategoryEnum;
use App\CustomerSupport\Domain\ValueObject\DateTimeAvailability;
use App\CustomerSupport\Domain\ValueObject\HoursRange;
use App\CustomerSupport\Domain\ValueObject\WorkLog;
use App\CustomerSupport\Domain\Repository\EmployeeAvailabilityRepositoryInterface;
use App\CustomerSupport\Domain\Repository\WorkLogRepositoryInterface;
use App\CustomerSupport\Domain\Repository\WorkScheduleRepositoryInterface;
use DateTimeInterface;

final class Employee
{
    private const DEFAULT_PERFORMANCE_TASKS_PER_HOUR = 5;
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly HoursRange $defaultWorkdayAvailability = new HoursRange(9, 17),
        public readonly HoursRange $defaultSaturdayAvailability = new HoursRange(9, 17),
        public readonly HoursRange $defaultSundayAvailability = HoursRange::empty(),
        public readonly int $maxHoursDaily = 10,
        public readonly int $maxHoursWeekly = 50,
        public readonly int $maxHoursMonthly = 160,
        /** @var QueueCategoryEnum[] */
        public array $supportedQueues = [],
        public EmployeeAvailabilityRepositoryInterface $availabilityRepository,
        public WorkLogRepositoryInterface $workLogRepository,
        public WorkScheduleRepositoryInterface $scheduleRepository,
    ) {
    }

    public function setDailyWorkPerformance(WorkLog $workLog): void
    {
        $this->workLogRepository->setWorkLog($this->id, $workLog);
    }

    public function scheduleWorkDay(DateTimeInterface $startDateTime, DateTimeInterface $endDateTime): void
    {
        $hoursRequired = $endDateTime->diff($startDateTime)->h;
        if ($hoursRequired < $this->hoursAvailableToSchedule($startDateTime)) {
            throw new \RuntimeException('Can not schedule workday, not enaught work hours available for this employee.');
        }

        if (!$this->getWorkHours($startDateTime)->isAvailableBetween($startDateTime, $endDateTime)) {
            $startTime = $startDateTime->format('Y-m-d H:i:s');
            $endTime = $endDateTime->format('Y-m-d H:i:s');
            throw new \RuntimeException(sprintf('Employee is not in work between %s and %s.', $startTime, $endTime));
        }

        $this->scheduleRepository->schedule($this->id, $startDateTime, $endDateTime);
    }

    public function performanceTaskPerHour(QueueCategoryEnum $queueCategory): float
    {
        $worklogs = $this->workLogRepository->getLastWorkLogs($this->id, $queueCategory, days: 30);

        $sum = $hours = 0;
        foreach($worklogs as $worklog) {
            foreach($worklog->tasksQuantityAtHours as $hour => $taskQuantity) {
                if (is_int($taskQuantity)) {
                    $sum += $taskQuantity;
                    $hours += 1;
                }
            }
        }

        return $hours > 0 ? $sum / $hours : self::DEFAULT_PERFORMANCE_TASKS_PER_HOUR;
    }

    public function setCustomWorkHours(DateTimeAvailability $availability): void
    {
        $this->availabilityRepository->add($this->id, $availability);
    }

    public function hoursAvailableToSchedule(DateTimeInterface $date): int
    {
        $workHoursCount = $this->getWorkHours($date)->hoursRange->hoursCount();
        if (0 === $workHoursCount
            || $this->scheduleRepository->hasScheduledWorkAt($this->id, $date)) {
            return 0;
        }

        $dailyHoursLeft =  min($workHoursCount, $this->maxHoursDaily);
        $weeklyHoursLeft = $this->maxHoursWeekly - $this->scheduleRepository->getHoursScheduledOnWeek($this->id, $date);
        $monthlyHoursLeft = $this->maxHoursMonthly - $this->scheduleRepository->getHoursScheduledOnMonth($this->id, $date);

        return max(0, min($dailyHoursLeft, $weeklyHoursLeft, $monthlyHoursLeft));
    }

    public function getWorkHours(DateTimeInterface $date): DateTimeAvailability
    {
        //day of week 0 for sunday
        $dayOfWeek = (int) $date->format("w");
        $defaultAvailability =  match ($dayOfWeek) {
            0 => $this->defaultSundayAvailability,
            6 => $this->defaultSaturdayAvailability,
            default => $this->defaultWorkdayAvailability,
        };
        return $this->availabilityRepository->find($this->id, $date) ?? $defaultAvailability;
    }

    public function isQueueSupported(QueueCategoryEnum $queue): bool
    {
        return in_array($queue, $this->supportedQueues, true);
    }
}