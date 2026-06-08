<?php

namespace App\Entity\Transaction;

use App\Entity\Account\Account;
use DateTimeImmutable;

class Transaction
{
    private int $id;

    private Account $fromAccount;

    private Account $toAccount;

    private string $amount;

    private TransactionStatusEnum $status;

    private DateTimeImmutable $createdAt;

    public function __construct(
        Account $fromAccount,
        Account $toAccount,
        string $amount,
        TransactionStatusEnum $status,
        DateTimeImmutable $createdAt = new DateTimeImmutable()
    ) {
        $this->fromAccount = $fromAccount;
        $this->toAccount = $toAccount;
        $this->amount = $amount;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFromAccount(): Account
    {
        return $this->fromAccount;
    }

    public function getToAccount(): Account
    {
        return $this->toAccount;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getStatus(): TransactionStatusEnum
    {
        return $this->status;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}