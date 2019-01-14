<?php

namespace App\Application\QueryHandler;

use App\Application\Query\GetFineRecommendationQuery;
use App\Domain\Service\FinePolicyInterface;

class GetFineRecommendationHandler
{
    private $finePolicy;

    public function __construct(FinePolicyInterface $finePolicy)
    {
        $this->finePolicy = $finePolicy;
    }

    public function __invoke(GetFineRecommendationQuery $query)
    {
        return $this->finePolicy->getFineRecommendation($query->getViolation());
    }
}