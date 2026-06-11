<?php

declare(strict_types=1);

namespace App\UseCase\CreateDeposit;

use App\Entity\Transaction;
use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;
use App\Exception\AccountNotFoundException;
use App\Repository\Accounts\AccountsRepositoryInterface;
use App\Repository\Transactions\TransactionsRepositoryInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class CreateDepositUseCase
{
    private $accountsRepository;
    private $transactionsRepository;
    private $entityManager;

    public function __construct(
        AccountsRepositoryInterface $accountsRepository,
        TransactionsRepositoryInterface $transactionsRepository,
        EntityManagerInterface $entityManager,
    ) {
        $this->accountsRepository = $accountsRepository;
        $this->transactionsRepository = $transactionsRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws AccountNotFoundException
     */
    public function execute(CreateDepositCommand $command): CreateDepositResult
    {
        return $this->entityManager->wrapInTransaction(function () use ($command) {
            $accountById = $this->accountsRepository->findById($command->getAccountId());
            if (null === $accountById) {
                throw AccountNotFoundException::create($command->getAccountId());
            }

            $accountById->setBalance($accountById->getBalance() + $command->getAmount());
            $this->accountsRepository->updateAccount($accountById);

            $transaction = new Transaction(
                $command->getAmount(),
                TransactionStatusEnum::COMPLETED,
                TransactionTypeEnum::DEPOSIT,
                new DateTimeImmutable(),
                null,
                $accountById,
            );
            $this->transactionsRepository->createTransaction($transaction);

            return new CreateDepositResult($accountById);
        });
    }
}
