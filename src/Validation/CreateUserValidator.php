<?php

declare(strict_types=1);

namespace App\Validation;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserValidator extends RequestValidation
{
    public function initialize(): void
    {
        $notBlankName = new Assert\NotBlank();
        $notBlankName->normalizer = function ($value) {
            if (is_string($value)) {
                return trim(strip_tags($value));
            }
            return $value;
        };

        $this->constraint = new Assert\Collection([
            'name' => [
                $notBlankName,
                new Assert\Type('string'),
                new Assert\Length(min: 2, max: 255),
            ],
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email(mode: Assert\Email::VALIDATION_MODE_HTML5),
            ],
        ]);
    }
}
