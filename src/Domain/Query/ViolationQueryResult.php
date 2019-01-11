<?php

namespace App\Domain\Query;

class ViolationQueryResult
{
    private $query;

    private $violations;

    private $total;

    public function __construct(ViolationQuery $query, iterable $violations, int $total)
    {
        $this->query = $query;
        $this->violations = $violations;
        $this->total = $total;
    }

    /**
     * @return mixed
     */
    public function getQuery(): ViolationQuery
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function getViolations(): iterable
    {
        return $this->violations;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
}