<?php

namespace App\Presentation\Http\DTO;

use App\Domain\Model\FineRecommendation;

final class FineRecommendationDTO
{
    public $count;
    public $fineAmount;
    public $critical;
    public $ruleDays;
    public $totalViolations;
    public $violations = [];
    public $violator;

    public function __construct(FineRecommendation $fineRecommendation, ViolatorDTO $violatorDTO)
    {
        foreach ($fineRecommendation->getPreviousViolations() as $violation) {
            $this->violations[] = new ViolationDTO($violation, $violatorDTO);

        }
        $this->count = count($this->violations);
        $this->violator = $violatorDTO;
        $this->totalViolations = $fineRecommendation->getTotalViolations();

        $this->critical = $fineRecommendation->getRule()->getCritical();
        $this->ruleDays = $fineRecommendation->getRule()->getDays();
        $this->fineAmount = $fineRecommendation->getFineAmount();
    }
}