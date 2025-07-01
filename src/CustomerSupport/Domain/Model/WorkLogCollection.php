<?php

declare(strict_types=1);

namespace App\CustomerSupport\Domain\Repository;

use App\CustomerSupport\Domain\Enum\QueueCategoryEnum;
use App\CustomerSupport\Domain\ValueObject\WorkLog;
use DateTimeInterface;

interface WorkLogRepositoryInterface
{
    public function add(WorkLog $workLog): void;

    public function setWorkLog(int $employeeId, WorkLog $workLog): void;

    /**
     * @return WorkLog[]
     */
    public function getLastWorkLogs(int $employeeId, QueueCategoryEnum $queueCategory, int $days = 30): array;
}