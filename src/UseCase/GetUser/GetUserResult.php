<?php

declare(strict_types=1);

namespace App\UseCase\GetUser;

use App\DTO\UserDTO;
use App\Entity\User;
use JsonSerializable;

/**
 * @OA\Schema(
 *     schema="GetUserResponse"
 * )
 */
class GetUserResult implements JsonSerializable
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
