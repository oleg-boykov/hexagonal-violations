<?php

namespace App\Application\CommandHandler;

use App\Application\Command\CreateSuggestionCommand;
use App\Application\SuggestionResultDTO;
use App\Domain\Model\Victim;
use App\Domain\Model\VictimType;
use App\Domain\Repository\RuleRepositoryInterface;
use App\Domain\Repository\Suggestion\ViolationSuggestionRepositoryInterface;
use App\Domain\Repository\SupportRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateSuggestionHandler
{
    private $supportRepo;
    private $ruleRepo;
    private $suggestionRepo;
    private $validator;

    public function __construct(
        SupportRepositoryInterface $supportRepository,
        RuleRepositoryInterface $ruleRepository,
        ViolationSuggestionRepositoryInterface $suggestionRepository,
        ValidatorInterface $validator
    ) {
        $this->supportRepo = $supportRepository;
        $this->ruleRepo = $ruleRepository;
        $this->suggestionRepo = $suggestionRepository;
        $this->validator = $validator;
    }

    public function __invoke(CreateSuggestionCommand $createSuggestion)
    {
        $errors = $this->validator->validate($createSuggestion);

        if (count($errors) > 0) {
            return new SuggestionResultDTO(null, $errors);
        }

        $manager = $this->supportRepo->find($createSuggestion->getOfferedBy());
        $violator = $this->supportRepo->find($createSuggestion->getViolatorId());
        $rule = $this->ruleRepo->find($createSuggestion->getRuleId());
        $suggestion = $manager->suggestViolation(
            $violator,
            new Victim($createSuggestion->getVictimId(), new VictimType($createSuggestion->getVictimType())),
            $rule,
            $createSuggestion->getComment()
        );

        $this->suggestionRepo->add($suggestion);

        return new SuggestionResultDTO($suggestion);
    }
}