<?php

namespace App\Validation;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;

abstract class RequestValidation
{
    protected $constraint;
    protected $groups;
    protected $validator;
    protected $data   = [];
    protected $errors = [];

    public function __construct(array $data)
    {
        $this->validator = Validation::createValidator();
        $this->data      = $data;

        $this->initialize();
    }

    public function isValid(): bool
    {
        $this->errors = $this->validator->validate($this->data, $this->constraint, $this->groups);
        return $this->errors->count() === 0;
    }

    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->errors as $error) {
            /** @var ConstraintViolation $error */
            $errors[] = [
                'code'    => $error->getCode(),
                'field'   => $error->getPropertyPath(),
                'message' => $error->getMessage(),
            ];
        }

        return $errors;
    }

    public function getValue(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function getFloatValue(string $key): ?float
    {
        if (!isset($this->data[$key])) {
            return null;
        }
        return (float)$this->data[$key];
    }

    public function getIntValue(string $key): ?int
    {
        if (!isset($this->data[$key])) {
            return null;
        }
        return (int)$this->data[$key];
    }

    public function getBoolValue(string $key): ?bool
    {
        if (!isset($this->data[$key])) {
            return null;
        }

        $value = $this->data[$key];
        if (in_array($value, ['false', false], true)) {
            return false;
        }

        return (bool)$this->data[$key];
    }

    abstract public function initialize();
}
