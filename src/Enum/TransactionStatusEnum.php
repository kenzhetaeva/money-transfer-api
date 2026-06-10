<?php

declare(strict_types=1);

namespace App\Enum;

enum TransactionStatusEnum: string
{
    case COMPLETED = 'completed';
    case PENDING = 'pending';
    case FAILED = 'failed';
}
