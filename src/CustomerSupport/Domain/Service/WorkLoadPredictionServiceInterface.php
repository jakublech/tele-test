<?php

namespace App\CustomerSupport\Domain\Service;

use App\CustomerSupport\Domain\ValueObject\WorkLog;
use App\CustomerSupport\Domain\Enum\QueueCategoryEnum;
use DateTimeInterface;


interface WorkLoadPredictionServiceInterface{

    public function predict(DateTimeInterface $date, QueueCategoryEnum $queueCategory): WorkLog;
}