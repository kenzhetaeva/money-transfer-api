<?php

declare(strict_types=1);

namespace App\UseCase\GetUserAccounts;

use App\Exception\UserNotFoundException;
use App\Repository\Users\UsersRepositoryInterface;

class GetUserAccountsUseCase
{
    private $usersRepository;

    public function __construct(
        UsersRepositoryInterface $usersRepository,
    ) {
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

        return new GetUserAccountsResult($user);
    }
}
