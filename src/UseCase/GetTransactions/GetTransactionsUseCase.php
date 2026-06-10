<?php

declare(strict_types=1);

namespace App\UseCase\GetTransactions;

use App\Exception\AccountNotFoundException;
use App\Repository\Accounts\AccountsRepositoryInterface;
use App\Repository\Transactions\TransactionsRepositoryInterface;

class GetTransactionsUseCase
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
     */
    public function execute(GetTransactionsCommand $command): GetTransactionsResult
    {
        $account = $this->accountsRepository->findById($command->getAccountId());

        if (null === $account) {
            throw AccountNotFoundException::create($command->getAccountId());
        }

        $transactions = $this->transactionsRepository->findByAccountId($command->getAccountId());

        return new GetTransactionsResult($transactions);
    }
}
