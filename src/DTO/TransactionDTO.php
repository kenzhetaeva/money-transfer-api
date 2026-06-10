<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Transaction;
use DateTimeImmutable;
use JsonSerializable;

class TransactionDTO extends BaseTransactionDTO implements JsonSerializable
{
    /**
     * @OA\Property(
     *     property="id",
     *     type="integer",
     *     description="Id of transaction",
     *     example=1
     * )
     */
    private $id;

    /**
     * @OA\Property(
     *     property="createdAt",
     *     type="string",
     *     description="Transaction creation date",
     *     example="2021-11-15T06:36:19+00:00"
     * )
     */
    private $createdAt;

    public function __construct(Transaction $transaction)
    {
        parent::__construct(
            $transaction->getFromAccount()?->getId(),
            $transaction->getToAccount()?->getId(),
            $transaction->getAmount(),
            $transaction->getStatus(),
            $transaction->getType(),
        );

        $this->id = $transaction->getId();
        $this->createdAt = $transaction->getCreatedAt();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'fromAccountId' => $this->fromAccountId,
            'toAccountId' => $this->toAccountId,
            'amount' => $this->amount,
            'status' => $this->status,
            'type' => $this->type,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
