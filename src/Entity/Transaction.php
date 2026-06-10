<?php

namespace App\Entity;

use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;
use DateTimeImmutable;

class Transaction
{
    private int $id;

    private ?Account $fromAccount;

    private ?Account $toAccount;

    private float $amount;

    private TransactionStatusEnum $status;

    private TransactionTypeEnum $type;

    private DateTimeImmutable $createdAt;

    public function __construct(
        float                 $amount,
        TransactionStatusEnum $status,
        TransactionTypeEnum   $type,
        DateTimeImmutable     $createdAt = new DateTimeImmutable(),
        ?Account              $fromAccount = null,
        ?Account              $toAccount = null,
    ) {
        $this->fromAccount = $fromAccount;
        $this->toAccount = $toAccount;
        $this->amount = $amount;
        $this->status = $status;
        $this->type = $type;
        $this->createdAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFromAccount(): ?Account
    {
        return $this->fromAccount;
    }

    public function getToAccount(): ?Account
    {
        return $this->toAccount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getStatus(): TransactionStatusEnum
    {
        return $this->status;
    }

    public function getType(): TransactionTypeEnum
    {
        return $this->type;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
