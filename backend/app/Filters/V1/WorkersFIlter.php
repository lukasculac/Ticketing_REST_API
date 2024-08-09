<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class WorkersFIlter extends ApiFilter
{
    protected $safeParms = [
        'name' => ['eq'],
        'email' => ['eq'],
        'position' => ['eq'],
        'phone' => ['eq'],
    ];
    protected $columnMap = [
    ];
    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!=',
    ];
}
