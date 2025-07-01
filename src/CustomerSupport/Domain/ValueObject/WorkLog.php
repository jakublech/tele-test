<?php

declare(strict_types=1);

namespace App\CustomerSupport\Domain\ValueObject;

use App\CustomerSupport\Domain\Enum\QueueCategoryEnum;
use App\CustomerSupport\Domain\ValueObject\HoursRange;
use DateTimeInterface;

final readonly class WorkLog
{
    public function __construct(
        public DateTimeInterface $date,
        public QueueCategoryEnum $queueCategory,
        /**
         * @param ?int[]|float[] $hourlyTasks Array of 24 integers, indexed 0-23, each representing task quantity done (as int) for that hour
        */
        public array $tasksQuantityAtHours = [],
    ) {
        if ([] === $tasksQuantityAtHours) {
            $tasksQuantityAtHours = array_fill(0, 24, null);
        }
        if (count($this->tasksQuantityAtHours) !== 24) {
            throw new \InvalidArgumentException('hourlyPredictions must have 24 elements, one for each hour (0-23).');
        }
        foreach ($tasksQuantityAtHours as $hour => $tasksQuantity) {
            if (!is_int($hour) || $hour < 0 || $hour > 23 ) {
                throw new \InvalidArgumentException('Hour should be int in range 0-23');
            }
            if (!is_int($tasksQuantity) && null !== $tasksQuantity && !is_float($tasksQuantity)) {
                throw new \InvalidArgumentException('Task Quantity should be int or float or null');
            }
        }
    }

    public function getTasksQuantityAtHour(int $hour): ?int
    {
        if ($hour < 0 || $hour > 23 ) {
            throw new \InvalidArgumentException('Hour should be int in range 0-23');
        }
        return $this->tasksQuantityAtHours[$hour];
    }

    public function getHoursRangeWhereTaskQuantityHigherThan(int $tasksMinimumLimit = 0): HoursRange
    {
        //filter array for hours where we have only hours with tasks above $taskMinimumLimit
        $filtered = array_filter($this->tasksQuantityAtHours, fn($tasksQuantity): bool => $tasksQuantity > $tasksMinimumLimit);

        $keys = array_keys($filtered);
        $beginHour = reset($keys);
        $endHour = end($keys);

        return new HoursRange((int) $beginHour, (int) $endHour);
    }
}
