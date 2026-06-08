<?php

declare(strict_types=1);

namespace App\UseCase\User;

/**
 * @OA\Schema(
 *     schema="CreateUserRequest"
 * )
 */
class CreateUserCommand
{
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

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
