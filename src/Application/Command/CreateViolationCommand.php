<?php

namespace App\Application\Command;

class CreateViolationCommand
{
    private $qualityManagerId;
    private $violatorId;
    private $ruleId;
    private $victimId;
    private $victimType;
    private $comment;

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

    /**
     * @param int $qualityManagerId
     */
    public function setQualityManager($qualityManagerId)
    {
        $this->qualityManagerId = $qualityManagerId;
    }

    /**
     * @return null
     */
    public function getQualityManagerId()
    {
        return $this->qualityManagerId;
    }

    /**
     * @return int
     */
    public function getViolatorId()
    {
        return $this->violatorId;
    }

    /**
     * @return int
     */
    public function getRuleId()
    {
        return $this->ruleId;
    }

    /**
     * @return int
     */
    public function getVictimId()
    {
        return $this->victimId;
    }

    /**
     * @return int
     */
    public function getVictimType()
    {
        return $this->victimType;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }
}
