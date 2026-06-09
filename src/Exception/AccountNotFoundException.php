<?php

namespace App\Exception;

use Exception;

class AccountNotFoundException extends Exception
{
    public static function create(int $accountId): AccountNotFoundException
    {
        return new self("Account with id '$accountId' does not exist", 404);
    }
}
