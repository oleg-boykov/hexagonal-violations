<?php

namespace App\Presentation\Http\DTO;

use App\Domain\Model\Violation;

final class ViolationDTO
{
    public $id;
    public $violator;
    public $violatorId;
    public $violatorFullName;
    public $offeredBy;
    public $processedBy;
    public $relatedViolations;
    public $comment;
    public $resolved;
    public $title;
    public $createdAt;
    public $violationableType;
    public $violationableId;
    public $ruleId;
    public $relations;

    public function __construct(Violation $violation, ViolatorDTO $violatorDTO)
    {
        $this->id = $violation->getId();
        $this->violatorId = $violatorDTO->id;
        $this->violatorFullName = $violatorDTO->fullName;
        $this->violator = $violatorDTO;
        $this->comment = $violation->getComment();
        $this->createdAt = $violation->getCreatedAt();
        $this->ruleId = $violation->getRule()->getId();
        $this->title = $violation->getRule()->getTitle();
        $this->resolved = (int) $violation->isResolved();
    }
}
