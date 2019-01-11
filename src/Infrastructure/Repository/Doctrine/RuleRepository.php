<?php

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\Model\Rule;
use App\Domain\Repository\RuleRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class RuleRepository implements RuleRepositoryInterface
{
    private $em;
    private $repo;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->repo = $entityManager->getRepository(Rule::class);
    }


    /**
     * @param int $id
     * @return Rule|null
     */
    public function find(int $id): ?Rule
    {
        return $this->repo->find($id);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->repo->findAll();
    }
}