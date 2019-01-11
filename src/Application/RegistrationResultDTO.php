<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 10.01.2019
 * Time: 14:19
 */

namespace App\Application;


use App\Domain\Model\Violation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class RegistrationResultDTO
{
    private $violation;
    private $errors;

    public function __construct(?Violation $violation = null, ?ConstraintViolationListInterface $errors = null)
    {
        $this->violation = $violation;
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

    public function getViolation(): ?Violation
    {
        return $this->violation;
    }
}