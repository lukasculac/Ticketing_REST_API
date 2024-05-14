<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class FilesFIlter extends ApiFilter
{

    protected $safeParms = [
        'ticketId' => ['eq'],
        'fileName' => ['eq'],
    ];
    protected $columnMap = [
        'ticketId' => 'ticket_id',
        'fileName' => 'file_name',
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
