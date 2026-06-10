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

    public function createTransaction(Transaction $transaction): void
    {
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}
