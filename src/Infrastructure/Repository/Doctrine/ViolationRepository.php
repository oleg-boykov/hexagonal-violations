<?php

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\Model\Rule;
use App\Domain\Model\Violation;
use App\Domain\Query\GetViolationsQuery;
use App\Domain\Query\ViolationQueryResult;
use App\Domain\Repository\ViolationRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ViolationRepository implements ViolationRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EntityRepository
     */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Violation::class);
    }

    public function find(int $id): ?Violation
    {
        return $this->repo->find($id);
    }

    public function findAll(): array
    {
        return $this->repo->findAll();
    }

    public function add(Violation $violation): void
    {
        $this->em->persist($violation);
    }

    public function remove(Violation $violation): void
    {
        $this->em->remove($violation);
    }

    public function findRepeatedForViolator(Rule $rule, int $violatorId): ?array
    {
        return $this
            ->repo
            ->createQueryBuilder('v')
            ->select()
            ->where('v.createdAt >= :createdAt')
            ->setParameter('createdAt', (new \DateTimeImmutable())->modify(sprintf("-%d days", $rule->getDays())))
            ->andWhere('v.violatorId >= :violatorId')
            ->setParameter('violatorId', $violatorId)
            ->andWhere('v.rule = :rule')
            ->setParameter('rule', $rule)
            ->getQuery()
            ->getResult();
    }

    public function countLastViolationsForViolator(int $violatorId, \DateTimeImmutable $dateTime): int
    {
        return $this
            ->repo
            ->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->where('v.createdAt >= :createdAt')
            ->setParameter('createdAt', $dateTime)
            ->andWhere('v.violatorId = :violatorId')
            ->setParameter('violatorId', $violatorId)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByQuery(GetViolationsQuery $query): ViolationQueryResult
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

        return new ViolationQueryResult($query, $paginator->getIterator(), $paginator->count());
    }
}