<?php

declare(strict_types=1);

namespace App\UseCase\CreateTransfer;

/**
 * @OA\Schema(
 *     schema="CreateTransferRequest"
 * )
 */
class CreateTransferCommand
{
    /**
     * @OA\Property(
     *     property="amount",
     *     type="float",
     *     description="The amount by which the account was replenished",
     *     example=123.45
     * )
     */
    private $amount;

    /**
     * @OA\Property(
     *     property="fromAccountId",
     *     type="integer",
     *     description="",
     *     example=1
     * )
     */
    private $fromAccountId;

    /**
     * @OA\Property(
     *     property="toAccountId",
     *     type="integer",
     *     description="",
     *     example=2
     * )
     */
    private $toAccountId;

    public function __construct(float $amount, int $fromAccountId, int $toAccountId)
    {
        $this->amount = $amount;
        $this->fromAccountId = $fromAccountId;
        $this->toAccountId = $toAccountId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getFromAccountId(): int
    {
        return $this->fromAccountId;
    }

    public function getToAccountId(): int
    {
        return $this->toAccountId;
    }
}
