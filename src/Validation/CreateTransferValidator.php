<?php

declare(strict_types=1);

namespace App\Validation;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTransferValidator extends RequestValidation
{
    public function initialize(): void
    {
        $this->constraint = new Assert\Collection([
            'amount' => [
                new Assert\Type(type: 'numeric'),
                new Assert\GreaterThan(value: 0),
            ],
            'fromAccountId' => [
                new Assert\Type(type: 'integer'),
                new Assert\GreaterThan(value: 0),
            ],
            'toAccountId' => [
                new Assert\Type(type: 'integer'),
                new Assert\GreaterThan(value: 0),
            ]
        ]);
    }
}
