<?php

namespace App\Application;

class RegisterSuggestionDTO
{
    public $offeredBy;
    public $violatorId;
    public $ruleId;
    public $victimId;
    public $victimType;
    public $comment;

    /**
     * RegisterViolationDTO constructor.
     * @param $offeredBy
     * @param $violatorId
     * @param $ruleId
     * @param $victimId
     * @param $victimType
     * @param $comment
     */
    public function __construct(
        $offeredBy = null,
        $violatorId = null,
        $ruleId = null,
        $victimId = null,
        $victimType = null,
        string $comment = null
    ) {
        $this->offeredBy = $offeredBy;
        $this->violatorId = $violatorId;
        $this->ruleId = $ruleId;
        $this->victimId = $victimId;
        $this->victimType = $victimType;
        $this->comment = $comment;
    }
}
