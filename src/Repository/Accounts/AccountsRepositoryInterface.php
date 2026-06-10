<?php

declare(strict_types=1);

namespace App\Repository\Accounts;

use App\Entity\Account;

interface AccountsRepositoryInterface
{
    public function findById(int $id): ?Account;

    /**
     * @return Account[]
     */
    public function getByUserId(int $userId): array;

    public function createAccount(Account $account);

    public function updateAccount(Account $account);
}
