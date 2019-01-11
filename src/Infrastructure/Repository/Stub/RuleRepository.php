<?php

namespace App\Infrastructure\Repository\Stub;

use App\Domain\Model\Rule;
use App\Domain\Repository\RuleRepositoryInterface;

class RuleRepository implements RuleRepositoryInterface
{
    /**
     * @var array
     */
    private $rules = [
        [1, 'Oops'],
        [2, 'Something happened'],
    ];

    /**
     * @param int $id
     * @return Rule|null
     */
    public function find(int $id): ?Rule
    {
        return new Rule($this->rules[$id][0], $this->rules[$id][1]);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return [
            new Rule($this->rules[0][0], $this->rules[0][1]),
            new Rule($this->rules[1][0], $this->rules[1][1]),
        ];
    }
}