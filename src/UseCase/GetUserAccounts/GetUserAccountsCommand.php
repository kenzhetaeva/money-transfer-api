<?php

declare(strict_types=1);

namespace App\UseCase\GetUserAccounts;

/**
 * @OA\Schema(
 *     schema="GetUserRequest"
 * )
 */
class GetUserAccountsCommand
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

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
