<?php

declare(strict_types=1);

namespace App\Entity\Account;

/**
 * @method static CurrencyEnum COMPLETED()
 * @method static CurrencyEnum PENDING()
 * @method static CurrencyEnum FAILED()
 */
class CurrencyEnum extends Enum
{
    private const USD = 'USD';
    private const EUR = 'EUR';
    private const KGS = 'KGS';
}