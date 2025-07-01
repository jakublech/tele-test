<?php

namespace App\CustomerSupport\Domain\Repository;

use App\CustomerSupport\Domain\Employee;
use Generator;

interface EmployeeRepositoryInterface{

    /**
     *  
     * @return Generator<int, Employee>
     */
    public function getAll(): Generator;
}