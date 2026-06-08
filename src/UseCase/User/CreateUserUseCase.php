<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Entity\User;
use App\Repository\Users\UsersRepositoryInterface;
use DateTimeImmutable;

class CreateUserUseCase
{
    private $usersRepository;

    public function __construct(
        UsersRepositoryInterface $usersRepository,
    ) {
        $this->usersRepository = $usersRepository;
    }

    public function execute(CreateUserCommand $command): void
    {
        $user = new User(
            $command->getName(),
            $command->getEmail(),
            new DateTimeImmutable(),
        );
        $this->usersRepository->createUser($user);
    }
}
