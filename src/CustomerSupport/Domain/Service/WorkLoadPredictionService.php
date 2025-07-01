<?php

declare(strict_types=1);

namespace App\CustomerSupport\Domain\Service;

use App\CustomerSupport\Domain\Enum\QueueCategoryEnum;
use App\CustomerSupport\Domain\Repository\WorkLogRepositoryInterface;
use App\CustomerSupport\Domain\ValueObject\WorkLog;
use DateTimeInterface;

final class WorkLoadPredictionService implements WorkLoadPredictionServiceInterface
{
    public function __construct(
        public WorkLogRepositoryInterface $workLogRepository,
        private $scalePrediction = 1.2,
        private $daysOfWeekBefore = 8,
    ) {}

    public function predict(DateTimeInterface $date, QueueCategoryEnum $queueCategory): WorkLog
    {
        $worklogs = $this->workLogRepository->getDayOfWeekFromLastWeeksAndQueue($date,  $this->daysOfWeekBefore, $queueCategory);
        $hourlyAverages = $this->getHourlyAverages($worklogs, $this->scalePrediction);

        return new WorkLog($date, $queueCategory, $hourlyAverages);
    }

        /**
     * @param WorkLog[] $arrayOfWorkLog
     */
    private function getHourlyAverages(array $arrayOfWorkLog, float $scale): array
    {
        $divisor = count($arrayOfWorkLog);
        $hourlySums = array_fill(0, 24, 0);

        if (0 === $divisor) {
            return $hourlySums;
        }

        foreach ($arrayOfWorkLog as $workLog) {
            for ($hour = 0; $hour < 24; $hour++) {
                $hourlySums[$hour] += $workLog->getTasksQuantityAtHour($hour) ?? 0;
            }
        }

        $hourlyAverages = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $hourlyAverages[$hour] = ($hourlySums[$hour] / $divisor) * $scale;
        }

        return $hourlyAverages;
    }
} 