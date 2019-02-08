<?php

namespace App\Application;

use App\Domain\Model\FineRecommendation;
use App\Domain\Model\Victim;
use App\Domain\Model\VictimType;
use App\Domain\Model\Violation;
use App\Domain\Repository\QualityManagerRepositoryInterface;
use App\Domain\Repository\RuleRepositoryInterface;
use App\Domain\Repository\Suggestion\ViolationSuggestionRepositoryInterface;
use App\Domain\Repository\SupportRepositoryInterface;
use App\Domain\Repository\ViolationRepositoryInterface;
use App\Domain\Service\FinePolicyInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ViolationRegistry
{

    private $qualityManagerRepo;
    private $supportRepo;
    private $ruleRepo;
    private $violationRepo;
    private $violationSuggestionRepo;
    private $finePolicy;
    private $validator;

    public function __construct(
        QualityManagerRepositoryInterface $qualityManagerRepository,
        SupportRepositoryInterface $supportRepository,
        RuleRepositoryInterface $ruleRepository,
        ViolationRepositoryInterface $violationRepository,
        ViolationSuggestionRepositoryInterface $violationSuggestionRepository,
        FinePolicyInterface $finePolicy,
        ValidatorInterface $validator
    ) {
        $this->qualityManagerRepo = $qualityManagerRepository;
        $this->supportRepo = $supportRepository;
        $this->ruleRepo = $ruleRepository;
        $this->violationRepo = $violationRepository;
        $this->violationSuggestionRepo = $violationSuggestionRepository;
        $this->finePolicy = $finePolicy;
        $this->validator = $validator;
    }


    /**
     * @param RegisterViolationDTO $dto
     * @return RegistrationResultDTO
     * @throws \Exception
     */
    public function register(RegisterViolationDTO $dto): RegistrationResultDTO
    {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return new RegistrationResultDTO(null, $errors);
        }

        $qualityManager = $this->qualityManagerRepo->find($dto->qualityManagerId);
        $violator = $this->supportRepo->find($dto->violatorId);
        $rule = $this->ruleRepo->find($dto->ruleId);
        $violation = $qualityManager->registerViolation(
            $violator,
            new Victim($dto->victimId, new VictimType($dto->victimType)),
            $rule,
            $dto->comment
        );
        $this->violationRepo->add($violation);

        return new RegistrationResultDTO($violation);
    }

    /**
     * @throws \Exception
     */
    public function suggest(RegisterSuggestionDTO $dto): SuggestionResultDTO
    {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return new SuggestionResultDTO(null, $errors);
        }
        $manager = $this->supportRepo->find($dto->offeredBy);
        $violator = $this->supportRepo->find($dto->violatorId);
        $rule = $this->ruleRepo->find($dto->ruleId);
        $suggestion = $manager->suggestViolation(
            $violator,
            new Victim($dto->victimId, new VictimType($dto->victimType)),
            $rule,
            $dto->comment
        );
        $this->violationSuggestionRepo->add($suggestion);

        return new SuggestionResultDTO($suggestion);
    }

    public function getFineRecommendation(Violation $violation): ?FineRecommendation
    {
        return $this->finePolicy->getFineRecommendation($violation);
    }

    public function delete(int $id)
    {
        $violation = $this->violationRepo->find($id);
        if ($violation) {
            $this->violationRepo->remove($violation);
        }
    }
}
