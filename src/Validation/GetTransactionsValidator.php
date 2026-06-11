<?php

declare(strict_types=1);

namespace App\Validation;

use App\Enum\CurrencyEnum;
use Symfony\Component\Validator\Constraints as Assert;

class GetTransactionsValidator extends RequestValidation
{
    public function initialize(): void
    {
        $this->constraint = new Assert\Collection([
            'perPage' => [
                new Assert\Type(type: 'integer'),
                new Assert\GreaterThanOrEqual(value: 1),
                new Assert\LessThanOrEqual(value: 100),
            ],
            'page' => [
                new Assert\Type(type: 'integer'),
                new Assert\GreaterThanOrEqual(value: 1),
            ],
        ]);
    }
}
