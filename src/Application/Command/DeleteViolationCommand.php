<?php

namespace App\Application\Command;

class DeleteViolationCommand
{
    private $violationId;

    public function __construct($violationId = null)
    {
        $this->violationId = $violationId;
    }

    /**
     * @return null
     */
    public function getViolationId(): int
    {
        return $this->violationId;
    }
}
