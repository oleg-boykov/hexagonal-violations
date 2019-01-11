<?php

namespace App\Domain\Model;

class FineRecommendation
{
    private $violation;
    private $previousViolations;
    private $totalViolations;
    private $fineAmount;

    /**
     * FineRecommendation constructor.
     *
     * @param Violation $violation
     * @param array $previousViolations
     * @param int $totalViolations
     * @param int $fineAmount
     */
    public function __construct(
        Violation $violation,
        array $previousViolations,
        int $totalViolations,
        int $fineAmount
    ) {
        $this->violation = $violation;
        $this->previousViolations = $previousViolations;
        $this->totalViolations = $totalViolations;
        $this->fineAmount = $fineAmount;
    }

    public function getViolation(): Violation
    {
        return $this->violation;
    }

    /**
     * @return Rule
     */
    public function getRule(): Rule
    {
        return $this->violation->getRule();
    }

    /**
     * @return array
     */
    public function getPreviousViolations(): array
    {
        return $this->previousViolations;
    }

    /**
     * @return int
     */
    public function getTotalViolations(): int
    {
        return $this->totalViolations;
    }

    /**
     * @return int
     */
    public function getFineAmount(): int
    {
        return $this->fineAmount;
    }
}
