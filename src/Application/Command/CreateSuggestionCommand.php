<?php

namespace App\Application\Command;

class CreateSuggestionCommand
{
    private $offeredBy;
    private $violatorId;
    private $ruleId;
    private $victimId;
    private $victimType;
    private $comment;

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

    /**
     * @param int $offeredBy
     */
    public function setOfferedBy($offeredBy)
    {
        $this->offeredBy = $offeredBy;
    }

    /**
     * @return int
     */
    public function getOfferedBy()
    {
        return $this->offeredBy;
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
     * @return null
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
