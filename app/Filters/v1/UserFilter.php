<?php

namespace App\Filters\v1;

use App\Filters\ApiFilter;

class UserFilter extends ApiFilter
{
    /**
     * @var array|\string[][]
     */
    protected array $allowedParams = [
        'name' => ['eq'],
        'email' => ['eq'],
        'emailVerifiedAt' => ['eq', 'lt', 'gt'],
        'password' => ['eq'],
        'fcmToken' => ['eq'],
        'rememberToken' => ['eq'],
        'createdAt' => ['eq', 'lt', 'gt'],
        'updatedAt' => ['eq', 'lt', 'gt']
    ];

    /**
     * @var array|string[]
     */
    protected array $columnMap = [
        'emailVerifiedAt' => 'email_verified_at',
        'fcmToken' => 'fcm_token',
        'rememberToken' => 'remember_token',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
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
