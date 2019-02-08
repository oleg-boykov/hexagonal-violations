<?php

namespace App\Presentation\Http\DTO;

use App\Domain\Model\Suggestion\ViolationSuggestion;
use App\Domain\Model\Support;

final class SuggestionDTO
{
    public $id;
    public $violator;
    public $violatorId;
    public $violatorFullName;
    public $offeredBy;
    public $prossedBy;
    public $relatedViolations;
    public $comment;
    public $status;
    public $title;
    public $createdAt;
    public $violationableType;
    public $violationableId;
    public $ruleId;
    public $relations;

    public function __construct(ViolationSuggestion $suggestion, ?Support $violator, ?Support $offerer)
    {
        $this->id = $suggestion->getId();
        $this->violatorId = $suggestion->getViolatorId();
        $this->violator = $violator ? sprintf("%s - %s", $violator->getId(), $violator->getFullName()) : "Unknown";
        $this->comment = $suggestion->getComment();
        $this->createdAt = $suggestion->getCreatedAt();
        $this->ruleId = $suggestion->getRule()->getId();
        $this->title = $suggestion->getRule()->getTitle();
        $this->offeredBy = $offerer ? sprintf("%s - %s", $offerer->getId(), $offerer->getFullName()) : "Unknown";
        $this->prossedBy = $suggestion->getProcessedBy();
        $this->status = $suggestion->getStatus();
    }
}
