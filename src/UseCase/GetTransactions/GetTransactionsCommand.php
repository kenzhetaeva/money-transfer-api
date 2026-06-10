<?php

declare(strict_types=1);

namespace App\UseCase\GetTransactions;

/**
 * @OA\Schema(
 *     schema="GetTransactionsRequest"
 * )
 */
class GetTransactionsCommand
{
    /**
     * @OA\Property(
     *     property="accountId",
     *     type="integer",
     *     description="Id of account",
     *     example=1
     * )
     */
    private $accountId;

    public function __construct(int $accountId)
    {
        $this->accountId = $accountId;
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }
}
