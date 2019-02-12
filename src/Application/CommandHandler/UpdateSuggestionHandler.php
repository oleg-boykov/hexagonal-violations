<?php

namespace App\Application\CommandHandler;

use App\Application\Command\UpdateSuggestionCommand;
use App\Domain\Model\Suggestion\Status;
use App\Domain\Model\Suggestion\ViolationSuggestion;
use App\Domain\Repository\Suggestion\ViolationSuggestionRepositoryInterface;

class UpdateSuggestionHandler
{
    private $suggestionRepository;

    public function __construct(ViolationSuggestionRepositoryInterface $suggestionRepository)
    {
        $this->suggestionRepository = $suggestionRepository;
    }

    public function __invoke(UpdateSuggestionCommand $command): ?ViolationSuggestion
    {
        $suggestion = $this->suggestionRepository->find($command->getId());
        if (!$suggestion) {
            return null;
        }
        switch ($command->getStatus()) {
            case Status::UNPROCESSED:
                $suggestion->unprocess();
                break;
            case Status::REJECTED:
                $suggestion->reject($command->getUserId(), $command->getComment());
                break;
            case Status::ACCEPTED:
                $suggestion->accept($command->getUserId());
                break;
            case Status::DELETED:
                $this->suggestionRepository->remove($suggestion);
                break;
        }

        return $suggestion;
    }
}