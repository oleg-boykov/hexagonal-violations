<?php

namespace App\Application\QueryHandler;

use App\Domain\Query\GetSuggestionQuery;
use App\Domain\Repository\Suggestion\ViolationSuggestionRepositoryInterface;

class GetSuggestionHandler
{
    private $suggestionRepository;

    public function __construct(ViolationSuggestionRepositoryInterface $suggestionRepository)
    {
        $this->suggestionRepository = $suggestionRepository;
    }

    public function __invoke(GetSuggestionQuery $query)
    {
        return $this->suggestionRepository->findByQuery($query);
    }
}
