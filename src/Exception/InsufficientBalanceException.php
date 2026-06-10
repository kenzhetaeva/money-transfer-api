<?php

namespace App\Exception;

use Exception;

class InsufficientBalanceException extends Exception
{
    public static function create(float $amount, float $balance): InsufficientBalanceException
    {
        return new self("Balance is too small. Balance is $balance. Amount: $amount");
    }
}
