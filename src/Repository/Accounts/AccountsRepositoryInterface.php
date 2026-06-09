<?php

declare(strict_types=1);

namespace App\Repository\Accounts;

use App\Entity\Account;

interface AccountsRepositoryInterface
{
    public function findById(int $id): ?Account;

    public function createAccount(Account $account);
}
