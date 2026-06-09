<?php

namespace App\Exception;

use Exception;

class DuplicatedEmailException extends Exception
{
    public static function create(string $email): DuplicatedEmailException
    {
        return new self("User with email '{$email}' already exists.", 409);
    }
}
