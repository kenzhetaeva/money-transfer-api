<?php

namespace App\Entity\Account;

use App\Entity\User\User;
use DateTimeImmutable;

class Account
{
    private int $id;

    private User $user;

    private CurrencyEnum $currency;

    private string $balance;

    private DateTimeImmutable $createdAt;

    public function __construct(
        User $user,
        CurrencyEnum $currency,
        string $balance,
        DateTimeImmutable $createdAt = new DateTimeImmutable()
    ) {
        $this->user = $user;
        $this->currency = $currency;
        $this->balance = $balance;
        $this->createdAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getCurrency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function getBalance(): string
    {
        return $this->balance;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}