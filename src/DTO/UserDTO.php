<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\User;
use DateTimeImmutable;
use JsonSerializable;

class UserDTO extends BaseUserDTO implements JsonSerializable
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
     *     property="createdAt",
     *     type="string",
     *     description="User creation date",
     *     example="2021-11-15T06:36:19+00:00"
     * )
     */
    private $createdAt;

    public function __construct(User $user)
    {
        parent::__construct(
            $user->getName(),
            $user->getEmail(),
        );

        $this->id = $user->getId();
        $this->createdAt = $user->getCreatedAt();
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
            'name' => $this->name,
            'email' => $this->email,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
