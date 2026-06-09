<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\User;
use DateTimeImmutable;
use JsonSerializable;

final class UserDTO implements JsonSerializable
{
    private int $id;
    private string $name;
    private string $email;
    private DateTimeImmutable $createdAt;

    public function __construct(int $id, string $name, string $email, DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->createdAt = $createdAt;
    }

    public static function fromEntity(User $user): self
    {
        return new self(
            $user->getId(),
            $user->getName(),
            $user->getEmail(),
            $user->getCreatedAt()
        );
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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}

