<?php

namespace App\Domain\Repository;

use App\Domain\Model\ViolationSuggestion;

interface ViolationSuggestionRepositoryInterface
{
    public function find(int $id): ?ViolationSuggestion;

    public function add(ViolationSuggestion $violation): void;
}
