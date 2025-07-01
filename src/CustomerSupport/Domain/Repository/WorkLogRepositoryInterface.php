<?php

declare(strict_types=1);

namespace App\CustomerSupport\Domain\Repository;

use App\CustomerSupport\Domain\Enum\QueueCategoryEnum;
use App\CustomerSupport\Domain\ValueObject\WorkLog;
use DateTimeInterface;

interface WorkLogRepositoryInterface
{
    public function save(int $employeeId, WorkLog $workLog): void;

    /** 
     * @return array|WorkLog[] 
     * Returns array of WorkLog of same day of week from previews number of $weeks
     * it may be used for calculation of average performance
     */
    public function getDayOfWeekFromLastWeeksAndQueue(DateTimeInterface $dateTime, int $weeks, ?QueueCategoryEnum $category): array;


    /**
     * @return WorkLog[]
     */
    public function getLastWorkLogs(int $employeeId, QueueCategoryEnum $queryCategory, int $days = 30): array;
}