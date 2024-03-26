<?php

namespace App\Filters\v1;

use App\Filters\ApiFilter;

class NoteFilter extends ApiFilter
{
    protected array $allowedParams = [
        'title' => ['eq'],
        'content' => ['eq'],
        'status' => ['eq'],
        'categories' => ['eq'],
        'priority' => ['eq', 'lt', 'gt'],
        'photoPath' => ['eq'],
    ];

    protected array $columnMap = [
        'photoPath' => 'photo_path'
    ];

    protected array $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>='
    ];
}
