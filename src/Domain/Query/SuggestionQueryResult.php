<?php

namespace App\Domain\Query;

class SuggestionQueryResult
{
    private $query;

    private $suggestions;

    private $total;

    public function __construct(GetSuggestionQuery $query, iterable $suggestions, int $total)
    {
        $this->query = $query;
        $this->suggestions = $suggestions;
        $this->total = $total;
    }

    /**
     * @return mixed
     */
    public function getQuery(): GetSuggestionQuery
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function getSuggestions(): iterable
    {
        return $this->suggestions;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
}