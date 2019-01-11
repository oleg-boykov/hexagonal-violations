<?php

namespace App\Application;

class RegisterViolationDTO
{
    public $qualityManagerId;
    public $violatorId;
    public $ruleId;
    public $victimId;
    public $victimType;
    public $comment;

    /**
     * RegisterViolationDTO constructor.
     * @param $qualityManagerId
     * @param $violatorId
     * @param $ruleId
     * @param $victimId
     * @param $victimType
     * @param $comment
     */
    public function __construct(
        $qualityManagerId = null,
        $violatorId = null,
        $ruleId = null,
        $victimId = null,
        $victimType = null,
        string $comment = null
    ) {
        $this->qualityManagerId = $qualityManagerId;
        $this->violatorId = $violatorId;
        $this->ruleId = $ruleId;
        $this->victimId = $victimId;
        $this->victimType = $victimType;
        $this->comment = $comment;
    }
}
