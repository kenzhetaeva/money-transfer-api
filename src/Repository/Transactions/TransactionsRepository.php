<?php

namespace App\Repository\Transactions;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;

class TransactionsRepository implements TransactionsRepositoryInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Transaction[]
     */
    public function findByAccountId(int $accountId): array
    {
        return $this->entityManager
            ->getRepository(Transaction::class)
            ->createQueryBuilder('a')
            ->where('a.fromAccount = :accountId')
            ->orWhere('a.toAccount = :accountId')
            ->setParameter('accountId', $accountId)
            ->getQuery()
            ->getResult();
    }

    public function createTransaction(Transaction $transaction): void
    {
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}
