<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class Victim
 *
 * @ORM\Embeddable()
 */
class Victim
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=true)
     */
    private $id;

    /**
     * @var VictimType
     *
     * @ORM\Column(name="type", type="enum", nullable=true, options={"class": "hello"})
     */
    private $type;

    public function __construct(?int $id, VictimType $type)
    {
        if (!is_null($id)) {
            Assert::greaterThan($id, 0);
        }
        $this->id = $id;
        $this->type = $type;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?VictimType
    {
        return $this->type;
    }

    public function isNull(): bool
    {
        return $this->id === null;
    }
}