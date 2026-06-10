<?php

declare(strict_types=1);

namespace App\UseCase\CreateTransfer;

use App\Entity\Transaction;
use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;
use App\Exception\AccountNotFoundException;
use App\Exception\InsufficientBalanceException;
use App\Repository\Accounts\AccountsRepositoryInterface;
use App\Repository\Transactions\TransactionsRepositoryInterface;
use DateTimeImmutable;

class CreateTransferUseCase
{
    private $accountsRepository;
    private $transactionsRepository;

    public function __construct(
        AccountsRepositoryInterface $accountsRepository,
        TransactionsRepositoryInterface $transactionsRepository,
    ) {
        $this->accountsRepository = $accountsRepository;
        $this->transactionsRepository = $transactionsRepository;
    }

    /**
     * @throws AccountNotFoundException
     * @throws InsufficientBalanceException
     */
    public function execute(CreateTransferCommand $command): CreateTransferResult
    {
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
    }
}
