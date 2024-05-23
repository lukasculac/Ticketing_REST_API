<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class AgentsFilter extends ApiFilter
{
    protected $safeParms = [
        'name' => ['eq'],
        'department' => ['eq'],
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
