<?php

declare(strict_types=1);

namespace App\UseCase\CreateUser;

use App\DTO\BaseUserDTO;

/**
 * @OA\Schema(
 *     schema="CreateUserRequest"
 * )
 */
class CreateUserCommand extends BaseUserDTO
{

    public function __construct(string $name, string $email)
    {
        parent::__construct($name, $email);
    }
}
