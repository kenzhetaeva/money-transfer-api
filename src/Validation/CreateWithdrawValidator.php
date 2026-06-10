<?php

declare(strict_types=1);

namespace App\Validation;

use Symfony\Component\Validator\Constraints as Assert;

class CreateWithdrawValidator extends RequestValidation
{
    public function initialize(): void
    {
        $this->constraint = new Assert\Collection([
            'amount' => [
                new Assert\Type(type: 'numeric'),
                new Assert\GreaterThan(value: 0),
            ],
        ]);
    }
}
