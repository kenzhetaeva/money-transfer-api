<?php

namespace App\Repository\Accounts;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;

class AccountsRepository implements AccountsRepositoryInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findById(int $id): ?Account
    {
        return $this->entityManager->find(Account::class, $id);
    }

    public function createAccount(Account $account): void
    {
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }
}
