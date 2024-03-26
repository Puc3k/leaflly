<?php

namespace App\Filters\v1;

use App\Filters\ApiFilter;

class PlantFilter extends ApiFilter
{
    /**
     * @var array|\string[][]
     */
    protected array $allowedParams = [
        'name' => ['eq'],
        'species' => ['eq'],
        'soilType' => ['eq'],
        'targetHeight' => ['eq', 'lt', 'gt'],
        'wateringFrequency' => ['eq', 'lt', 'gt'],
        'lastWatered' => ['eq', 'lt', 'gt'],
        'insolation' => ['eq'],
        'cultivationDifficulty' => ['eq']
    ];

    /**
     * @var array|string[]
     */
    protected array $columnMap = [
        'soilType' => 'soil_type',
        'targetHeight' => 'target_height',
        'wateringFrequency' => 'watering_frequency',
        'lastWatered' => 'last_watered',
        'cultivationDifficulty' => 'cultivation_difficulty'
    ];

    /**
     * @var array|string[]
     */
    protected array $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>='
    ];
}
