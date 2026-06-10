<?php

declare(strict_types=1);

namespace App\Validation;

use App\Enum\CurrencyEnum;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAccountValidator extends RequestValidation
{
    public function initialize(): void
    {
        $this->constraint = new Assert\Collection([
            'userId' => [
                new Assert\Type(type: 'integer'),
                new Assert\GreaterThan(value: 0),
            ],
            'currency' => [
                new Assert\NotBlank(),
                new Assert\Choice(
                    choices: array_column(CurrencyEnum::cases(), 'value')
                ),
            ],
            'balance' => [
                new Assert\Type(type: 'numeric'),
                new Assert\GreaterThanOrEqual(value: 0),
            ],
        ]);
    }
}
