<?php

namespace App\Entity\User;

use DateTimeImmutable;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private DateTimeImmutable $createdAt;

    public function __construct($id, $name, $email, $createdAt) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->createdAt = $createdAt;
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
}
