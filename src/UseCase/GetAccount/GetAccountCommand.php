<?php

declare(strict_types=1);

namespace App\UseCase\GetAccount;

/**
 * @OA\Schema(
 *     schema="GetAccountRequest"
 * )
 */
class GetAccountCommand
{
    /**
     * @OA\Property(
     *     property="id",
     *     type="integer",
     *     description="Id of account",
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
