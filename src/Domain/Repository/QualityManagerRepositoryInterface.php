<?php

namespace App\Domain\Repository;

use App\Domain\Model\QualityManager;

interface QualityManagerRepositoryInterface
{
    public function find(int $id): ?QualityManager;
}
