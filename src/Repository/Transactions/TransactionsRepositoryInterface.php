<?php

declare(strict_types=1);

namespace App\Repository\Transactions;

use App\Entity\Transaction;

interface TransactionsRepositoryInterface
{
    /**
     * @return Transaction[]
     */
    public function findByAccountId(int $accountId): array;

    public function createTransaction(Transaction $transaction);
}
