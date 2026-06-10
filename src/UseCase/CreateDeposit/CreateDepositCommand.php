<?php

declare(strict_types=1);

namespace App\UseCase\CreateDeposit;

/**
 * @OA\Schema(
 *     schema="CreateDepositRequest"
 * )
 */
class CreateDepositCommand
{
    /**
     * @OA\Property(
     *     property="accountId",
     *     type="integer",
     *     description="Replenished account",
     *     example=1
     * )
     */
    private $accountId;

    /**
     * @OA\Property(
     *     property="amount",
     *     type="float",
     *     description="The amount by which the account was replenished",
     *     example=123.45
     * )
     */
    private $amount;

    public function __construct(int $accountId, float $amount)
    {
        $this->accountId = $accountId;
        $this->amount = $amount;
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
