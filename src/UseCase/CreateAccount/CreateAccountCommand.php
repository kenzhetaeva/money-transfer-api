<?php

declare(strict_types=1);

namespace App\UseCase\CreateAccount;

use App\Enum\CurrencyEnum;

/**
 * @OA\Schema(
 *     schema="CreateAccountRequest"
 * )
 */
class CreateAccountCommand
{
    /**
     * @OA\Property(
     *     property="userId",
     *     type="integer",
     *     description="User to whom belongs account",
     *     example=1
     * )
     */
    private $userId;

    /**
     * @OA\Property(
     *     property="currency",
     *     type="string",
     *     description="Currency of account",
     *     example="USD"
     * )
     */
    private $currency;

    /**
     * @OA\Property(
     *     property="balance",
     *     type="float",
     *     description="Balance of account",
     *     example=123.45
     * )
     */
    private $balance;

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
