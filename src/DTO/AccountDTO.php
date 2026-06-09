<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Account;
use App\Enum\CurrencyEnum;
use DateTimeImmutable;
use JsonSerializable;

class AccountDTO implements JsonSerializable
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

    /**
     * @OA\Property(
     *     property="createdAt",
     *     type="string",
     *     description="Account creation date",
     *     example="2021-11-15T06:36:19+00:00"
     * )
     */
    private $createdAt;

    public function __construct(Account $account)
    {
        $this->id = $account->getId();
        $this->currency = $account->getCurrency();
        $this->balance = $account->getBalance();
        $this->createdAt = $account->getCreatedAt();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCurrency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'currency' => $this->currency,
            'balance' => $this->balance,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
