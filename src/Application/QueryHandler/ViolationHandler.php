<?php

namespace App\Application\QueryHandler;

use App\Domain\Query\ViolationQuery;
use App\Domain\Repository\ViolationRepositoryInterface;

class ViolationHandler
{
    private $violationRepository;

    public function __construct(ViolationRepositoryInterface $violationRepository)
    {
        $this->violationRepository = $violationRepository;
    }

    public function __invoke(ViolationQuery $query)
    {
        return $this->violationRepository->findByQuery($query);
    }
}
