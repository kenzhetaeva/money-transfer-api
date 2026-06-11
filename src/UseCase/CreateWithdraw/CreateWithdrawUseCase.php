<?php

declare(strict_types=1);

namespace App\UseCase\CreateWithdraw;

use App\Entity\Transaction;
use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;
use App\Exception\AccountNotFoundException;
use App\Exception\InsufficientBalanceException;
use App\Repository\Accounts\AccountsRepositoryInterface;
use App\Repository\Transactions\TransactionsRepositoryInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class CreateWithdrawUseCase
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
     * @throws InsufficientBalanceException
     */
    public function execute(CreateWithdrawCommand $command): CreateWithdrawResult
    {
        return $this->entityManager->wrapInTransaction(function () use ($command) {
            $accountById = $this->accountsRepository->findById($command->getAccountId());
            if (null === $accountById) {
                throw AccountNotFoundException::create($command->getAccountId());
            }

            if ($accountById->getBalance() < $command->getAmount()) {
                throw InsufficientBalanceException::create($command->getAmount(), $accountById->getBalance());
            }

            $accountById->setBalance($accountById->getBalance() - $command->getAmount());
            $this->accountsRepository->updateAccount($accountById);

            $transaction = new Transaction(
                $command->getAmount(),
                TransactionStatusEnum::COMPLETED,
                TransactionTypeEnum::WITHDRAW,
                new DateTimeImmutable(),
                $accountById,
                null,
            );
            $this->transactionsRepository->createTransaction($transaction);

            return new CreateWithdrawResult($accountById);
        });
    }
}
