<?php

namespace App\Domain\Model;

class QualityManager
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param Support $violator
     * @param Victim $victim
     * @param Rule $rule
     * @param string $comment
     * @return Violation
     * @throws \Exception
     */
    public function registerViolation(Support $violator, Victim $victim, Rule $rule, string $comment = ''): Violation
    {
        return new Violation($violator->getId(), $victim, $rule, $this->id, $comment);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
