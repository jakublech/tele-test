<?php

namespace App\CustomerSupport\Domain\Enum;


enum QueueCategoryEnum: string
{
    case SALES = "SALES";
    case TECH_SUPPORT = "TECH_SUPPORT";
    case COMPLAINTS = "COMPLAINTS";
    case BILLING = "BILLING";
}