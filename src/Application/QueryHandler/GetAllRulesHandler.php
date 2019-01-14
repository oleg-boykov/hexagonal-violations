<?php

namespace App\Application\QueryHandler;

use App\Application\Query\GetAllRulesQuery;
use App\Domain\Repository\RuleRepositoryInterface;

class GetAllRulesHandler
{
    private $ruleRepository;

    public function __construct(RuleRepositoryInterface $ruleRepository)
    {
        $this->ruleRepository = $ruleRepository;
    }

    public function __invoke(GetAllRulesQuery $query)
    {
        return $this->ruleRepository->findAll();
    }
}
