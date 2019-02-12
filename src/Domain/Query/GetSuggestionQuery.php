<?php

namespace App\Domain\Query;

class GetSuggestionQuery
{
    private $page;
    private $perPage;
    private $ruleId;
    private $violatorId;
    private $startDate;
    private $endDate;

    /**
     * GetSuggestionQuery constructor.
     *
     * @param int $perPage
     * @param int $page
     * @param int|null $ruleId
     * @param int|null $violatorId
     * @param \DateTime|null $startDate
     * @param \DateTime|null $endDate
     */
    public function __construct(
        int $page,
        int $perPage,
        int $ruleId = null,
        int $violatorId = null,
        \DateTime $startDate = null,
        \DateTime $endDate = null
    ) {
        $this->page = $page;
        $this->perPage = $perPage;
        $this->ruleId = $ruleId;
        $this->violatorId = $violatorId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    public function getRuleId(): ?int
    {
        return $this->ruleId;
    }

    public function getViolatorId(): ?int
    {
        return $this->violatorId;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }
}
