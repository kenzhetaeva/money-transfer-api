<?php

declare(strict_types=1);

namespace App\UseCase\GetAccount;

use App\Exception\AccountNotFoundException;
use App\Repository\Accounts\AccountsRepositoryInterface;

class GetAccountUseCase
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
    public function execute(GetAccountCommand $command): GetAccountResult
    {
        $account = $this->accountsRepository->findById($command->getId());

        if (null === $account) {
            throw AccountNotFoundException::create($command->getId());
        }

        return new GetAccountResult($account);
    }
}
