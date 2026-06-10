<?php

declare(strict_types=1);

namespace App\UseCase\CreateDeposit;

use App\Exception\AccountNotFoundException;
use App\Repository\Accounts\AccountsRepositoryInterface;

class CreateDepositUseCase
{
    private $accountsRepository;

    public function __construct(
        AccountsRepositoryInterface $accountsRepository,
    ) {
        $this->accountsRepository = $accountsRepository;
    }

    /**
     * @throws AccountNotFoundException
     */
    public function execute(CreateDepositCommand $command): CreateDepositResult
    {
        $accountById = $this->accountsRepository->findById($command->getAccountId());
        if (null === $accountById) {
            throw AccountNotFoundException::create($command->getAccountId());
        }

        $accountById->setBalance($accountById->getBalance() + $command->getAmount());
        $this->accountsRepository->updateAccount($accountById);
        return new CreateDepositResult($accountById);
    }
}
