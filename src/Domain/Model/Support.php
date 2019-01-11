<?php

namespace App\Domain\Model;

use App\Domain\Model\Suggestion\ViolationSuggestion;

class Support
{
    private $id;
    private $fullName;
    private $hourlyWage;

    /**
     * Support constructor.
     * @param $id
     * @param $fullName
     * @param $hourlyWage
     */
    public function __construct($id, $fullName, $hourlyWage)
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->hourlyWage = $hourlyWage;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getHourlyWage(): string
    {
        return $this->hourlyWage;
    }

    /**
     * @param Support $violator
     * @param Rule $rule
     * @param Victim $victim
     * @param string $comment
     * @return ViolationSuggestion
     * @throws \Exception
     */
    public function suggestViolation(Support $violator, Victim $victim, Rule $rule, string $comment = ''): ViolationSuggestion
    {
        return new ViolationSuggestion($violator->getId(), $victim, $rule, $this->id, $comment);
    }
}
