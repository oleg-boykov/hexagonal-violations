<?php

namespace App\Application;

use App\Domain\Model\Suggestion\ViolationSuggestion;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class SuggestionResultDTO
{
    private $suggestion;
    private $errors;

    public function __construct(?ViolationSuggestion $suggestion = null, ?ConstraintViolationListInterface $errors = null)
    {
        $this->suggestion = $suggestion;
        $this->errors = $errors;
    }

    public function hasErrors(): bool
    {
        if (is_null($this->errors)) {
            return false;
        }
        return count($this->errors) > 0;
    }

    public function getErrors(): ?ConstraintViolationListInterface
    {
        return $this->errors;
    }

    public function getSuggestion(): ?ViolationSuggestion
    {
        return $this->suggestion;
    }
}