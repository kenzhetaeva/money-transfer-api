<?php

declare(strict_types=1);

namespace App\UseCase\CreateWithdraw;

use App\DTO\AccountDTO;
use App\Entity\Account;
use JsonSerializable;

/**
 * @OA\Schema(
 *     schema="CreateWithdrawResponse"
 * )
 */
class CreateWithdrawResult implements JsonSerializable
{
    private AccountDTO $account;

    public function __construct(Account $account)
    {
        $this->account = new AccountDTO($account);
    }

    public function jsonSerialize(): array
    {
        return $this->account->jsonSerialize();
    }
}
