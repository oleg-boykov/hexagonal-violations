<?php

namespace App\Infrastructure\Repository\Stub;

use App\Domain\Model\Violation;
use App\Domain\Model\ViolationSuggestion;
use App\Domain\Repository\ViolationSuggestionRepositoryInterface;

class ViolationSuggestionRepository implements ViolationSuggestionRepositoryInterface
{
    public function find(int $id): ?ViolationSuggestion
    {
        // TODO: Implement find() method.
    }

    public function add(ViolationSuggestion $violation): void
    {
        // TODO: Implement add() method.
    }
}