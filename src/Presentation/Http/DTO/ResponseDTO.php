<?php

namespace App\Presentation\Http\DTO;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ResponseDTO
 */
final class ResponseDTO
{
    private $data = [];
    private $errors = [];
    private $alerts = [];
    private $status = self::OK;

    public const OK = 200;
    public const ACCEPTED = 202;
    public const ERROR = 400;

    /**
     * @param string $error
     *
     * @return ResponseDTO
     */
    public function addError(string $error): self
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @param string $alert
     *
     * @return ResponseDTO
     */
    public function addAlert(string $alert): self
    {
        $this->alerts[] = $alert;

        return $this;
    }

    /**
     * @param mixed $data
     *
     * @return ResponseDTO
     */
    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param int $status
     *
     * @return ResponseDTO
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getAlerts(): array
    {
        return $this->alerts;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param array $alerts
     *
     * @return ResponseDTO
     */
    public function setAlerts(array $alerts): self
    {
        $this->alerts = $alerts;

        return $this;
    }

    /**
     * @param array|ConstraintViolationListInterface $errors
     *
     * @return ResponseDTO
     */
    public function setErrors($errors): self
    {
        if (is_array($errors)) {
            $this->errors = $errors;
        }
        if ($errors instanceof ConstraintViolationListInterface) {
            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $this->addError($error->getMessage());
            }
        }

        return $this;
    }
}