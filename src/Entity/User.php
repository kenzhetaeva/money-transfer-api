<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private DateTimeImmutable $createdAt;
    private Collection $accounts;

    public function __construct(
        string $name,
        string $email,
        DateTimeImmutable $createdAt = new DateTimeImmutable()
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->createdAt = $createdAt;
        $this->accounts = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getAccounts(): Collection
    {
        return $this->accounts;
    }
}
