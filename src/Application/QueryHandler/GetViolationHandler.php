<?php

namespace App\Application\QueryHandler;

use App\Domain\Query\GetViolationsQuery;
use App\Domain\Repository\ViolationRepositoryInterface;

class GetViolationHandler
{
    private $violationRepository;

    public function __construct(ViolationRepositoryInterface $violationRepository)
    {
        $this->violationRepository = $violationRepository;
    }

    public function __invoke(GetViolationsQuery $query)
    {
        return $this->violationRepository->findByQuery($query);
    }
}
