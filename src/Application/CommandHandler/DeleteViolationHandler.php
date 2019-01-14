<?php

namespace App\Application\CommandHandler;

use App\Application\Command\DeleteViolationCommand;
use App\Domain\Repository\ViolationRepositoryInterface;

class DeleteViolationHandler
{
    private $violationRepository;

    public function __construct(ViolationRepositoryInterface $violationRepository)
    {
        $this->violationRepository = $violationRepository;
    }

    public function __invoke(DeleteViolationCommand $command)
    {
        $violation = $this->violationRepository->find($command->getViolationId());
        if ($violation) {
            $this->violationRepository->remove($violation);
        }
    }
}