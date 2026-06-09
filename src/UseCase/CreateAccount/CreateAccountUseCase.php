<?php

declare(strict_types=1);

namespace App\UseCase\CreateAccount;

use App\Entity\Account;
use App\Exception\UserNotFoundException;
use App\Repository\Accounts\AccountsRepositoryInterface;
use App\Repository\Users\UsersRepositoryInterface;

class CreateAccountUseCase
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
    public function execute(CreateAccountCommand $command): CreateAccountResult
    {
        $userById = $this->usersRepository->findById($command->getUserId());
        if (null === $userById) {
            throw UserNotFoundException::create($command->getUserId());
        }

        $account = new Account(
            $userById,
            $command->getCurrency(),
            $command->getBalance(),
        );
        $this->accountsRepository->createAccount($account);
        return new CreateAccountResult($account);
    }
}
