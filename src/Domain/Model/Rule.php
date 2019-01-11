<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Rule
 *
 * @ORM\Entity()
 * @ORM\Table("support_rule")
 */
class Rule
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
     * @var string
     *
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="critical", type="smallint")
     */
    private $critical;

    /**
     * @var int
     *
     * @ORM\Column(name="fine_percent", type="smallint", nullable=true)
     */
    private $finePercent;

    /**
     * @var int
     *
     * @ORM\Column(name="days", type="smallint")
     */
    private $days;

    /**
     * @var int
     *
     * @ORM\Column(name="wh", type="smallint", nullable=true)
     */
    private $workingHours;

    /**
     * Rule constructor.
     * @param $id
     * @param $title
     */
    public function __construct(int $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getDays(): int
    {
        return $this->days;
    }

    /**
     * @return int
     */
    public function getCritical(): int
    {
        return $this->critical;
    }

    /**
     * @return int
     */
    public function getFinePercent(): ?int
    {
        return $this->finePercent;
    }

    /**
     * @return int
     */
    public function getWorkingHours(): ?int
    {
        return $this->workingHours;
    }
}
