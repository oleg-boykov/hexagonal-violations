<?php

namespace App\Application\CommandHandler;

use App\Application\Command\ResolveViolationCommand;
use App\Domain\Model\Violation;
use App\Domain\Repository\ViolationRepositoryInterface;

class ResolveViolationHandler
{
    private $violationRepository;

    public function __construct(ViolationRepositoryInterface $violationRepository)
    {
        $this->violationRepository = $violationRepository;
    }

    public function __invoke(ResolveViolationCommand $command): ?Violation
    {
        $violation = $this->violationRepository->find($command->getViolationId());
        if (!$violation) {
            return null;
        }
        if ($command->isResolve()) {
            $violation->resolve();
        } else {
            $violation->unresolve();
        }

        return $violation;
    }
}