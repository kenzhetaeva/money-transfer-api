<?php

declare(strict_types=1);

namespace App\Entity\Transaction;

use App\Entity\Enum;

/**
 * @method static TransactionStatusEnum COMPLETED()
 * @method static TransactionStatusEnum PENDING()
 * @method static TransactionStatusEnum FAILED()
 */
class TransactionStatusEnum extends Enum
{
    private const COMPLETED = 'completed';
    private const PENDING = 'pending';
    private const FAILED = 'failed';
}
