<?php

namespace App\Application\CommandHandler;

use App\Application\Command\CreateViolationCommand;
use App\Application\RegistrationResultDTO;
use App\Domain\Model\Victim;
use App\Domain\Model\VictimType;
use App\Domain\Repository\QualityManagerRepositoryInterface;
use App\Domain\Repository\RuleRepositoryInterface;
use App\Domain\Repository\SupportRepositoryInterface;
use App\Domain\Repository\ViolationRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateViolationHandler
{
    private $qualityManagerRepo;
    private $supportRepo;
    private $ruleRepo;
    private $violationRepo;
    private $validator;

    public function __construct(
        QualityManagerRepositoryInterface $qualityManagerRepository,
        SupportRepositoryInterface $supportRepository,
        RuleRepositoryInterface $ruleRepository,
        ViolationRepositoryInterface $violationRepository,
        ValidatorInterface $validator
    ) {
        $this->qualityManagerRepo = $qualityManagerRepository;
        $this->supportRepo = $supportRepository;
        $this->ruleRepo = $ruleRepository;
        $this->violationRepo = $violationRepository;
        $this->validator = $validator;
    }

    public function __invoke(CreateViolationCommand $createViolation)
    {
        $errors = $this->validator->validate($createViolation);

        if (count($errors) > 0) {
            return new RegistrationResultDTO(null, $errors);
        }

        $qualityManager = $this->qualityManagerRepo->find($createViolation->getQualityManagerId());
        $violator = $this->supportRepo->find($createViolation->getViolatorId());
        $rule = $this->ruleRepo->find($createViolation->getRuleId());
        $violation = $qualityManager->registerViolation(
            $violator,
            new Victim($createViolation->getVictimId(), new VictimType($createViolation->getVictimType())),
            $rule,
            $createViolation->getComment()
        );
        $this->violationRepo->add($violation);

        return new RegistrationResultDTO($violation);
    }
}