<?php

declare(strict_types=1);

namespace App\UseCase\GetTransactions;

use App\DTO\TransactionDTO;
use App\Entity\Transaction;
use JsonSerializable;

/**
 * @OA\Schema(
 *     schema="GetTransactionsResponse"
 * )
 */
class GetTransactionsResult implements JsonSerializable
{
    /**
     * @var TransactionDTO[]
     */
    private array $transactions;

    /**
     * @param Transaction[] $transactions
     */
    public function __construct(array $transactions)
    {
        $this->transactions = array_map(
            static fn (Transaction $transaction) => new TransactionDTO($transaction),
            $transactions
        );
    }

    public function jsonSerialize(): array
    {
        return $this->transactions;
    }
}
