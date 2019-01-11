<?php

namespace App\Infrastructure\Repository\Stub;

use App\Domain\Model\Rule;
use App\Domain\Model\Violation;
use App\Domain\Query\ViolationQuery;
use App\Domain\Query\ViolationQueryResult;
use App\Domain\Repository\ViolationRepositoryInterface;

class ViolationRepository implements ViolationRepositoryInterface
{
    public function find(int $id): ?Violation
    {
        return null;
    }

    public function add(Violation $violation): void
    {
    }

    public function findPrevious(Violation $violation): ?array
    {
    }

    public function findAll(): array
    {
        return [];
    }

    public function remove(Violation $violation): void
    {
    }

    public function findRepeatedForViolator(Rule $rule, int $violatorId): ?array
    {
        return [];
    }

    public function countLastViolationsForViolator(int $violatorId, \DateTimeImmutable $dateTime): int
    {
        return 1;
    }

    public function findByQuery(ViolationQuery $query): ViolationQueryResult
    {
        return new ViolationQueryResult($query, [], 0);
    }
}