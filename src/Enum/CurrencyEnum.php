<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * @method static CurrencyEnum USD()
 * @method static CurrencyEnum EUR()
 * @method static CurrencyEnum KGS()
 */
class CurrencyEnum extends Enum
{
    private const USD = 'USD';
    private const EUR = 'EUR';
    private const KGS = 'KGS';
}
