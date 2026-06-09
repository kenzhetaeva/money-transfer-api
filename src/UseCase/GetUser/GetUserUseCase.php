<?php

declare(strict_types=1);

namespace App\UseCase\GetUser;

use App\Exception\UserNotFoundException;
use App\Repository\Users\UsersRepositoryInterface;
use App\DTO\UserDTO;

class GetUserUseCase
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
    public function execute(GetUserCommand $command): GetUserResult
    {
        $user = $this->usersRepository->findById($command->getId());

        if (null === $user) {
            throw UserNotFoundException::create($command->getId());
        }

        $userDto = UserDTO::fromEntity($user);
        return new GetUserResult($userDto);
    }
}
