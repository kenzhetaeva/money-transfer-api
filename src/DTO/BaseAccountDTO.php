<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enum\CurrencyEnum;

class BaseAccountDTO
{
    /**
     * @OA\Property(
     *     property="userId",
     *     type="integer",
     *     description="User to whom belongs account",
     *     example=1
     * )
     */
    protected $userId;

    /**
     * @OA\Property(
     *     property="currency",
     *     type="string",
     *     description="Currency of account",
     *     example="USD"
     * )
     */
    protected $currency;

    /**
     * @OA\Property(
     *     property="balance",
     *     type="float",
     *     description="Balance of account",
     *     example=123.45
     * )
     */
    protected $balance;

    public function __construct(int $userId, CurrencyEnum $currency, float $balance)
    {
        $this->userId = $userId;
        $this->currency = $currency;
        $this->balance = $balance;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCurrency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
}
