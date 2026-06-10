<?php

declare(strict_types=1);

namespace App\Repository\Transactions;

use App\Entity\Transaction;

interface TransactionsRepositoryInterface
{
    public function createTransaction(Transaction $transaction);
}
