<?php

namespace App\Domain\Model\Suggestion;

use App\Domain\Model\Rule;
use App\Domain\Model\Victim;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ViolationSuggestion
 *
 * @ORM\Entity()
 * @ORM\Table(name="support_suggestion")
 */
class ViolationSuggestion
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
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Rule")
     */
    private $rule;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment = '';

    /**
     * @var string
     *
     * @ORM\Column(name="reject_comment", type="text")
     */
    private $rejectComment = '';

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="text")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="processed_by", type="integer", nullable=true)
     */
    private $processedBy;

    /**
     * @var integer
     *
     * @ORM\Column(name="offered_by", type="integer")
     */
    private $offeredBy;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(name="created_at", type="datetime_immutable")
     */
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

    public function reject(int $processedBy, string $rejectComment)
    {
        $this->processedBy = $processedBy;
        $this->rejectComment = $rejectComment;
        $this->status = Status::REJECTED;
    }

    public function unprocess()
    {
        $this->processedBy = null;
        $this->rejectComment = '';
        $this->status = Status::UNPROCESSED;
    }

    public function accept(int $processedBy)
    {
        $this->processedBy = $processedBy;
        $this->status = Status::ACCEPTED;
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
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getProcessedBy(): ?int
    {
        return $this->processedBy;
    }

    /**
     * @return int
     */
    public function getOfferedBy(): int
    {
        return $this->offeredBy;
    }
}