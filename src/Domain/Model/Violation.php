<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Violation
 *
 * @ORM\Entity()
 * @ORM\Table(name="support_violation")
 */
class Violation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="violator_id", type="integer")
     */
    private $violatorId;

    /**
     * @var Victim
     *
     * @ORM\Embedded(class="App\Domain\Model\Victim")
     */
    private $victim;

    /**
     * @var Rule
     *
     * @ORM\ManyToOne(targetEntity="Rule")
     */
    private $rule;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="resolved", type="boolean")
     */
    private $resolved = false;

    /**
     * @var integer
     *
     * @ORM\Column(name="quality_manager_id", type="integer")
     */
    private $qualityManagerId;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(name="created_at", type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(name="seen_at", type="datetime_immutable", nullable=true)
     */
    private $seenAt;

    /**
     * Violation constructor.
     * @param int $violatorId
     * @param Victim $victim
     * @param Rule $rule
     * @param int $qualityManagerId
     * @param string $comment
     * @throws \Exception
     */
    public function __construct(int $violatorId, Victim $victim, Rule $rule, int $qualityManagerId, string $comment = '')
    {
        $this->violatorId = $violatorId;
        $this->victim = $victim;
        $this->rule = $rule;
        $this->qualityManagerId = $qualityManagerId;
        $this->comment = $comment;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function resolve(): void
    {
        $this->resolved = true;
    }

    public function unresolve(): void
    {
        $this->resolved = false;
    }

    /**
     * @throws \Exception
     */
    public function markSeen()
    {
        $this->seenAt = new \DateTimeImmutable();
    }

    public function getSeenAt()
    {
        return $this->seenAt;
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
     * @return bool
     */
    public function isResolved(): bool
    {
        return $this->resolved;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getFineRecommendation(FinePolicyInterface $finePolicy): FineRecommendation
    {

    }
}
