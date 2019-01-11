<?php

namespace App\Domain\Model;

class VictimType
{
    const ORDER = 1;
    const USER = 2;

    private $type;

    public function __construct(?int $type)
    {
        $this->type = $type;
    }

    public static function Order()
    {
        return new self(self::ORDER);
    }

    public static function User()
    {
        return new self(self::USER);
    }

    public function getValue(): ?int
    {
        return $this->type;
    }
}