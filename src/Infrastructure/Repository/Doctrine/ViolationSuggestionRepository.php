<?php

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\Model\Suggestion\ViolationSuggestion;
use App\Domain\Query\GetSuggestionQuery;
use App\Domain\Query\SuggestionQueryResult;
use App\Domain\Repository\Suggestion\ViolationSuggestionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ViolationSuggestionRepository implements ViolationSuggestionRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * ViolationSuggestionRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(ViolationSuggestion::class);
    }
    public function add(ViolationSuggestion $violation): void
    {
        $this->em->persist($violation);
    }

    public function findByQuery(GetSuggestionQuery $query): SuggestionQueryResult
    {
        $queryBuilder = $this
            ->repo
            ->createQueryBuilder('v')
            ->select()
            ->setMaxResults($query->getPerPage())
            ->setFirstResult(($query->getPage() - 1) * $query->getPerPage());

        if ($ruleId = $query->getRuleId()) {
            $queryBuilder->andWhere('v.rule = :ruleId');
            $queryBuilder->setParameter('ruleId', $ruleId);
        }
        if ($violatorId = $query->getViolatorId()) {
            $queryBuilder->andWhere('v.violatorId = :violatorId');
            $queryBuilder->setParameter('violatorId', $violatorId);
        }
        if ($endDate = $query->getEndDate()) {
            $queryBuilder->andWhere('v.createdAt <= :endDate');
            $queryBuilder->setParameter('endDate', $endDate);
        }
        if ($startDate = $query->getStartDate()) {
            $queryBuilder->andWhere('v.createdAt >= :startDate');
            $queryBuilder->setParameter('startDate', $startDate);
        }
        $paginator = new Paginator($queryBuilder);

        return new SuggestionQueryResult($query, $paginator->getIterator(), $paginator->count());
    }

    public function find(int $id): ?ViolationSuggestion
    {
        return $this->repo->find($id);
    }

    public function remove(ViolationSuggestion $violation): void
    {
        $this->em->remove($violation);
    }
}