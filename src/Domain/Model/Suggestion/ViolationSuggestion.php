<?php

namespace App\Domain\Model\Suggestion;

use App\Domain\Model\Rule;
use App\Domain\Model\Victim;

class ViolationSuggestion
{
    private $violatorId;
    private $victim;
    private $rule;
    private $comment = '';
    private $status;
    private $proccessedBy;
    private $offeredBy;
    private $createdAt;

    /**
     * Violation constructor.
     * @param int $violatorId
     * @param Victim $victim
     * @param Rule $rule
     * @param int $managerId
     * @param string $comment
     * @throws \Exception
     */
    public function __construct(int $violatorId, Victim $victim, Rule $rule, int $managerId, string $comment = '')
    {
        $this->violatorId = $violatorId;
        $this->victim = $victim;
        $this->rule = $rule;
        $this->offeredBy = $managerId;
        $this->comment = $comment;
        $this->status = Status::UNPROCESSED;
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * @return integer
     */
    public function getViolatorId(): int
    {
        return $this->violatorId;
    }

    /**
     * @return Victim
     */
    public function getVictim(): Victim
    {
        return $this->victim;
    }

    /**
     * @return Rule
     */
    public function getRule(): Rule
    {
        return $this->rule;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}