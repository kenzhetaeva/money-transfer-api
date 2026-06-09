<?php

declare(strict_types=1);

namespace App\UseCase\GetUserAccounts;

use App\Exception\UserNotFoundException;
use App\Repository\Accounts\AccountsRepositoryInterface;
use App\Repository\Users\UsersRepositoryInterface;

class GetUserAccountsUseCase
{
    private $accountsRepository;
    private $usersRepository;

    public function __construct(
        AccountsRepositoryInterface $accountsRepository,
        UsersRepositoryInterface $usersRepository,
    ) {
        $this->accountsRepository = $accountsRepository;
        $this->usersRepository = $usersRepository;
    }

    /**
     * @throws UserNotFoundException
     */
    public function execute(GetUserAccountsCommand $command): GetUserAccountsResult
    {
        $user = $this->usersRepository->findById($command->getId());

        if (null === $user) {
            throw UserNotFoundException::create($command->getId());
        }
        $accounts = $this->accountsRepository->getByUserId($command->getId());

        return new GetUserAccountsResult($accounts);
    }
}
