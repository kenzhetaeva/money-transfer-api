<?php

declare(strict_types=1);

namespace App\UseCase\CreateTransfer;

use App\DTO\AccountDTO;
use App\Entity\Account;
use JsonSerializable;

/**
 * @OA\Schema(
 *     schema="CreateTransferResponse"
 * )
 */
class CreateTransferResult implements JsonSerializable
{
    /**
     * @var AccountDTO[]
     */
    private array $accounts;

    /**
     * @param Account[] $accounts
     */
    public function __construct(array $accounts)
    {
        $this->accounts = array_map(
            static fn (Account $account) => new AccountDTO($account),
            $accounts
        );
    }

    public function jsonSerialize(): array
    {
        return $this->accounts;
    }
}
