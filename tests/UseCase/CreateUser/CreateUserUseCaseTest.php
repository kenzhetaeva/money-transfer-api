<?php

declare(strict_types=1);

namespace App\Tests\UseCase\CreateUser;

use PHPUnit\Framework\TestCase;
use App\UseCase\CreateUser\CreateUserUseCase;
use App\UseCase\CreateUser\CreateUserCommand;
use App\Repository\Users\UsersRepositoryInterface;
use App\Entity\User;
use App\Exception\DuplicatedEmailException;
use DateTimeImmutable;

class CreateUserUseCaseTest extends TestCase
{
    public function testExecuteThrowsDuplicatedEmailExceptionWhenEmailExists(): void
    {
        $name = 'John Doe';
        $email = 'test@example.com';

        $existingUser = new User($name, $email, new DateTimeImmutable());

        $usersRepository = $this->createMock(UsersRepositoryInterface::class);
        $usersRepository->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($existingUser);

        $useCase = new CreateUserUseCase($usersRepository);

        $this->expectException(DuplicatedEmailException::class);

        $useCase->execute(new CreateUserCommand($name, $email));
    }
}
