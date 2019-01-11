<?php

namespace App\Infrastructure\Repository\Stub;

use App\Domain\Model\QualityManager;
use App\Domain\Repository\QualityManagerRepositoryInterface;

class QualityManagerRepository implements QualityManagerRepositoryInterface
{
    public function find(int $id): ?QualityManager
    {
        return new QualityManager($id);
    }
}
