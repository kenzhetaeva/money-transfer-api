<?php

declare(strict_types=1);

namespace App\UseCase\GetUser;

use App\Entity\User;
use DateTimeImmutable;
use JsonSerializable;

/**
 * @OA\Schema(
 *     schema="GetUserResponse"
 * )
 */
class GetUserResult implements JsonSerializable
{
    /**
     * @OA\Property(
     *     property="id",
     *     type="integer",
     *     description="Id of user",
     *     example=1
     * )
     */
    private $id;

    /**
     * @OA\Property(
     *     property="name",
     *     type="string",
     *     description="Name of user",
     *     example="John Doe"
     * )
     */
    private $name;

    /**
     * @OA\Property(
     *     property="email",
     *     type="string",
     *     description="Email of user",
     *     example="johndoe@gmail.com"
     * )
     */
    private $email;

    /**
     * @OA\Property(
     *     property="createdAt",
     *     type="string",
     *     description="User creation date",
     *     example="2021-11-15T06:36:19+00:00"
     * )
     */
    private $createdAt;

    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->name = $user->getName();
        $this->email = $user->getEmail();
        $this->createdAt = $user->getCreatedAt();
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
