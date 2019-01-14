<?php

namespace App\Application\Command;

class ResolveViolationCommand
{
    private $violationId;
    private $resolve = false;

    public function __construct($violationId = null, $resolve = false)
    {
        $this->violationId = $violationId;
        $this->resolve = $resolve;
    }

    /**
     * @return null
     */
    public function getViolationId(): int
    {
        return $this->violationId;
    }

    /**
     * @return bool
     */
    public function isResolve(): bool
    {
        return $this->resolve;
    }
}
