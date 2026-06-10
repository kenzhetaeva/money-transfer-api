<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;

class BaseTransactionDTO
{
    /**
     * @OA\Property(
     *     property="fromAccountId",
     *     type="integer",
     *     description="Transaction from account id",
     *     example=1
     * )
     */
    protected $fromAccountId;

    /**
     * @OA\Property(
     *     property="toAccountId",
     *     type="integer",
     *     description="Transaction to account id",
     *     example=2
     * )
     */
    protected $toAccountId;

    /**
     * @OA\Property(
     *     property="amount",
     *     type="float",
     *     description="Amount of transaction",
     *     example=123.45
     * )
     */
    protected $amount;

    /**
     * @OA\Property(
     *     property="status",
     *     type="string",
     *     description="Status of transation",
     *     example="completed"
     * )
     */
    protected $status;

    /**
     * @OA\Property(
     *     property="type",
     *     type="string",
     *     description="Type of transaction",
     *     example="transfer"
     * )
     */
    protected $type;

    public function __construct(
        ?int $fromAccountId,
        ?int $toAccountId,
        float $amount,
        TransactionStatusEnum $status,
        TransactionTypeEnum  $type
    ) {
        $this->fromAccountId = $fromAccountId;
        $this->toAccountId = $toAccountId;
        $this->amount = $amount;
        $this->status = $status;
        $this->type = $type;
    }

    public function getFromAccountId(): ?int
    {
        return $this->fromAccountId;
    }

    public function getToAccountId(): ?int
    {
        return $this->toAccountId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getStatus(): TransactionStatusEnum
    {
        return $this->status;
    }

    public function getType(): TransactionTypeEnum
    {
        return $this->type;
    }
}
