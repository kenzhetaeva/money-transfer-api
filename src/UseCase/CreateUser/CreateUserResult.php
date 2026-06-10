<?php

declare(strict_types=1);

namespace App\UseCase\CreateUser;

use App\DTO\UserDTO;
use App\Entity\User;
use JsonSerializable;

/**
 * @OA\Schema(
 *     schema="CreateUserResponse"
 * )
 */
class CreateUserResult implements JsonSerializable
{
    private UserDTO $user;

    public function __construct(User $user)
    {
        $this->user = new UserDTO($user);
    }

    public function jsonSerialize(): array
    {
        return $this->user->jsonSerialize();
    }
}
