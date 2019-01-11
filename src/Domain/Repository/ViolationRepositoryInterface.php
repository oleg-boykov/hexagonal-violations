<?php

namespace App\Domain\Repository;

use App\Domain\Model\Rule;
use App\Domain\Model\Violation;
use App\Domain\Query\ViolationQuery;
use App\Domain\Query\ViolationQueryResult;

interface ViolationRepositoryInterface
{
    public function find(int $id): ?Violation;

    public function add(Violation $violation): void;

    public function findAll(): array;

    public function findRepeatedForViolator(Rule $rule, int $violatorId): ?array;

    public function findByQuery(ViolationQuery $query): ViolationQueryResult;

    public function countLastViolationsForViolator(int $violatorId, \DateTimeImmutable $dateTime): int;

    public function remove(Violation $violation): void;
}
