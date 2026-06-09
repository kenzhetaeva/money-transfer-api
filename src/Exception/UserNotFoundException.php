<?php

namespace App\Exception;

use Exception;

class UserNotFoundException extends Exception
{
    public static function create(int $userId): UserNotFoundException
    {
        return new self("User with id '$userId' does not exist", 404);
    }
}
