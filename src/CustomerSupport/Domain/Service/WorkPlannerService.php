<?php

namespace App\CustomerSupport\Service;

use App\CustomerSupport\Domain\Enum\QueueCategoryEnum;
use App\CustomerSupport\Domain\Service\WorkLoadPredictionServiceInterface;
use App\CustomerSupport\Domain\Repository\WorkScheduleRepositoryInterface;
use App\CustomerSupport\Domain\Repository\EmployeeRepositoryInterface;
use DateTime;
use DateTimeInterface;
use RuntimeException;

class WorkPlannerService
{
    public function __construct(
        private WorkLoadPredictionServiceInterface $workLoadPredictionService,
        private EmployeeRepositoryInterface $employeeRepository,
        private WorkScheduleRepositoryInterface $workScheduleRepository,
    )
    {
    }

    public function generateForPeriod(DateTimeInterface $dateFrom, DateTimeInterface $dateTo, QueueCategoryEnum $queueCategory): void
    {
        $days = $dateFrom->diff($dateTo)->days;
        for ($i = 0; $i < $days; $i++) {
            $date = (new DateTime($dateFrom->format(DATE_ATOM)))->modify("+$i days");
            $this->generateForDate($date, $queueCategory);
        }
    }

    public function generateForDate(DateTimeInterface $date, QueueCategoryEnum $queueCategory): void  
    {
        $workLoadPrediction = $this->workLoadPredictionService->predict($date, $queueCategory);
        $predictedWorkHoursRange = $workLoadPrediction->getHoursRangeWhereTaskQuantityHigherThan(0);
        $beginHourToPlan = max(0, $predictedWorkHoursRange->hourFrom - 1); //one hour before prediction, not less than 0
        $endHourToPlan = min(24, $predictedWorkHoursRange->hourTo + 1); //one hour after prediction, not more than 24

        foreach ($this->employeeRepository->getAll() as $employee) {
            if (false === $employee->isQueueSupported($queueCategory)) {
                continue;
            }
            $startTime = new DateTime($date->format('Y-m-d ').$beginHourToPlan.':00:00');
            $endTime = new DateTime($date->format('Y-m-d ').$endHourToPlan.':00:00');

            try {
                $employee->scheduleWorkDay($startTime, $endTime);
            } catch (\Exception $e) {
                continue;
            }
            
            $coveredTasksPerformance += $employee->performanceTaskPerHour($queueCategory);
            $notCoveredHours = $workLoadPrediction->getHoursRangeWhereTaskQuantityHigherThan($coveredTasksPerformance);
            if (0 === $notCoveredHours->hoursCount()) {
                return;
            }

            $beginHourToPlan = $notCoveredHours->hourFrom;
            $endHourToPlan = $notCoveredHours->hourTo;
        }

        throw new RuntimeException('Not enaught employees to cover work load.');
    }

}