<?php

namespace App\Exception;

use Exception;

class InvalidAccountsException extends Exception
{
    public static function create(): InvalidAccountsException
    {
        return new self("Provided invalid account ids");
    }
}
