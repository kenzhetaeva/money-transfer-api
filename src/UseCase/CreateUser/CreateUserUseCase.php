<?php

declare(strict_types=1);

namespace App\UseCase\CreateUser;

use App\Entity\User;
use App\Exception\DuplicatedEmailException;
use App\Repository\Users\UsersRepositoryInterface;
use App\DTO\UserDTO;
use DateTimeImmutable;

class CreateUserUseCase
{
    private $usersRepository;

    public function __construct(
        UsersRepositoryInterface $usersRepository,
    ) {
        $this->usersRepository = $usersRepository;
    }

    /**
     * @throws DuplicatedEmailException
     */
    public function execute(CreateUserCommand $command): CreateUserResult
    {
        $userByEmail = $this->usersRepository->findByEmail($command->getEmail());
        if ($userByEmail) {
            throw DuplicatedEmailException::create($command->getEmail());
        }

        $user = new User(
            $command->getName(),
            $command->getEmail(),
            new DateTimeImmutable(),
        );
        $this->usersRepository->createUser($user);
        $dto = UserDTO::fromEntity($user);
        return new CreateUserResult($dto);
    }
}
