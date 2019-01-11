<?php

namespace App\Domain\Repository;

use App\Domain\Model\Rule;

interface RuleRepositoryInterface
{
    public function find(int $id): ?Rule;

    public function findAll(): array;
}
