<?php

namespace App\Domain\Service;

use App\Domain\Model\FineRecommendation;
use App\Domain\Model\Violation;

interface FinePolicyInterface
{
    public function getFineRecommendation(Violation $violation): ?FineRecommendation;
}
