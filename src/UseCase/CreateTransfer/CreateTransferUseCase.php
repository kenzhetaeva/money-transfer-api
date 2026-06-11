<?php

declare(strict_types=1);

namespace App\UseCase\CreateTransfer;

use App\Entity\Transaction;
use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;
use App\Exception\AccountNotFoundException;
use App\Exception\InsufficientBalanceException;
use App\Exception\InvalidAccountsException;
use App\Repository\Accounts\AccountsRepositoryInterface;
use App\Repository\Transactions\TransactionsRepositoryInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class CreateTransferUseCase
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
     * @throws InvalidAccountsException
     * @throws AccountNotFoundException
     * @throws InsufficientBalanceException
     */
    public function execute(CreateTransferCommand $command): CreateTransferResult
    {
        if ($command->getFromAccountId() === $command->getToAccountId()) {
            throw InvalidAccountsException::create();
        }

        return $this->entityManager->wrapInTransaction(function () use ($command) {
            $fromAccount = $this->accountsRepository->findById($command->getFromAccountId());
            if (null === $fromAccount) {
                throw AccountNotFoundException::create($command->getFromAccountId());
            }

            $toAccount = $this->accountsRepository->findById($command->getToAccountId());
            if (null === $toAccount) {
                throw AccountNotFoundException::create($command->getToAccountId());
            }

            if ($fromAccount->getBalance() < $command->getAmount()) {
                throw InsufficientBalanceException::create($command->getAmount(), $fromAccount->getBalance());
            }

            $fromAccount->setBalance($fromAccount->getBalance() - $command->getAmount());
            $this->accountsRepository->updateAccount($fromAccount);

            $toAccount->setBalance($toAccount->getBalance() + $command->getAmount());
            $this->accountsRepository->updateAccount($toAccount);

            $transaction = new Transaction(
                $command->getAmount(),
                TransactionStatusEnum::COMPLETED,
                TransactionTypeEnum::TRANSFER,
                new DateTimeImmutable(),
                $fromAccount,
                $toAccount,
            );
            $this->transactionsRepository->createTransaction($transaction);

            return new CreateTransferResult([$fromAccount, $toAccount]);
        });
    }
}
