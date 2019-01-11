<?php

namespace App\Domain\Repository;

use App\Domain\Model\Support;

interface SupportRepositoryInterface
{
    public function find(int $id): ?Support;

    public function findByIds(array $ids): array;
}
