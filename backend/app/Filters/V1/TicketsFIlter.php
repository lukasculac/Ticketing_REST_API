<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class TicketsFIlter extends ApiFilter
{


    protected $safeParms = [
        'workerId' => ['eq'],
        'department' => ['eq'],
        'message' => ['eq'],
        'status' => ['eq', 'ne'],
        'priority' => ['eq', 'ne'],
        'openedAt' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'closedAt' => ['eq', 'lt', 'lte', 'gt', 'gte'],

    ];

    protected $columnMap = [
        'workerId' => 'worker_id',
        'openedAt' => 'opened_at',
        'closedAt' => 'closed_at',
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
