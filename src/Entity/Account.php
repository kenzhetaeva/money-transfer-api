<?php

namespace App\Entity;

use App\Enum\CurrencyEnum;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Account
{
    private int $id;

    private User $user;

    private CurrencyEnum $currency;

    private float $balance;

    private DateTimeImmutable $createdAt;

    private Collection $outgoingTransactions;

    private Collection $incomingTransactions;

    public function __construct(
        User $user,
        CurrencyEnum $currency,
        float $balance,
        DateTimeImmutable $createdAt = new DateTimeImmutable()
    ) {
        $this->user = $user;
        $this->currency = $currency;
        $this->balance = $balance;
        $this->createdAt = $createdAt;
        $this->outgoingTransactions = new ArrayCollection();
        $this->incomingTransactions = new ArrayCollection();
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

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getOutgoingTransactions(): Collection
    {
        return $this->outgoingTransactions;
    }

    public function getIncomingTransactions(): Collection
    {
        return $this->incomingTransactions;
    }
}
