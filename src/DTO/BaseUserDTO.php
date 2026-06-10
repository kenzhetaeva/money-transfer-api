<?php

declare(strict_types=1);

namespace App\DTO;

class BaseUserDTO
{
    /**
     * @OA\Property(
     *     property="name",
     *     type="string",
     *     description="Name of user",
     *     example="John Doe"
     * )
     */
    protected $name;

    /**
     * @OA\Property(
     *     property="email",
     *     type="string",
     *     description="Email of user",
     *     example="johndoe@gmail.com"
     * )
     */
    protected $email;

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
