<?php

namespace App\Domain\Repository\Suggestion;

use App\Domain\Model\Suggestion\ViolationSuggestion;
use App\Domain\Query\GetSuggestionQuery;
use App\Domain\Query\SuggestionQueryResult;

interface ViolationSuggestionRepositoryInterface
{
    public function find(int $id): ?ViolationSuggestion;

    public function add(ViolationSuggestion $violation): void;

    public function findByQuery(GetSuggestionQuery $query): SuggestionQueryResult;

    public function remove(ViolationSuggestion $violation): void;
}
