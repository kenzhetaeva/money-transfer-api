<?php

declare(strict_types=1);

namespace App\Tests\UseCase\CreateTransfer;

use App\Entity\Account;
use App\Entity\User;
use App\Enum\CurrencyEnum;
use App\Exception\AccountNotFoundException;
use App\Exception\InsufficientBalanceException;
use App\Exception\InvalidAccountsException;
use App\Repository\Accounts\AccountsRepositoryInterface;
use App\Repository\Transactions\TransactionsRepositoryInterface;
use App\UseCase\CreateTransfer\CreateTransferCommand;
use App\UseCase\CreateTransfer\CreateTransferUseCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CreateTransferUseCaseTest extends TestCase
{
    private $accountsRepository;
    private $transactionsRepository;
    private $entityManager;
    private $useCase;

    protected function setUp(): void
    {
        $this->accountsRepository = $this->createMock(AccountsRepositoryInterface::class);
        $this->transactionsRepository = $this->createMock(TransactionsRepositoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->entityManager->method('wrapInTransaction')
            ->willReturnCallback(fn(callable $callback) => $callback());

        $this->useCase = new CreateTransferUseCase(
            $this->accountsRepository,
            $this->transactionsRepository,
            $this->entityManager
        );
    }

    public function testExecuteThrowsInvalidAccountsExceptionWhenSameAccount(): void
    {
        $command = new CreateTransferCommand(100.0, 1, 1);

        $this->expectException(InvalidAccountsException::class);

        $this->useCase->execute($command);
    }

    public function testExecuteThrowsAccountNotFoundExceptionWhenFromAccountNotFound(): void
    {
        $command = new CreateTransferCommand(100.0, 1, 2);

        $this->accountsRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(null);

        $this->expectException(AccountNotFoundException::class);

        $this->useCase->execute($command);
    }

    public function testExecuteThrowsAccountNotFoundExceptionWhenToAccountNotFound(): void
    {
        $command = new CreateTransferCommand(100.0, 1, 2);

        $fromAccount = new Account(new User('User 1', 'u1@example.com'), CurrencyEnum::USD, 500.0);

        $this->accountsRepository->method('findById')
            ->willReturnMap([
                [1, $fromAccount],
                [2, null]
            ]);

        $this->expectException(AccountNotFoundException::class);

        $this->useCase->execute($command);
    }

    public function testExecuteThrowsInsufficientBalanceException(): void
    {
        $command = new CreateTransferCommand(100.0, 1, 2);

        $fromAccount = new Account(new User('User 1', 'u1@example.com'), CurrencyEnum::USD, 50.0);
        $toAccount = new Account(new User('User 2', 'u2@example.com'), CurrencyEnum::USD, 200.0);

        $this->accountsRepository->method('findById')
            ->willReturnMap([
                [1, $fromAccount],
                [2, $toAccount]
            ]);

        $this->expectException(InsufficientBalanceException::class);

        $this->useCase->execute($command);
    }

    public function testExecuteSuccess(): void
    {
        $command = new CreateTransferCommand(100.0, 1, 2);

        $user1 = new User('User 1', 'u1@example.com');
        $this->setId($user1, 1);
        $fromAccount = new Account($user1, CurrencyEnum::USD, 500.0);
        $this->setId($fromAccount, 1);

        $user2 = new User('User 2', 'u2@example.com');
        $this->setId($user2, 2);
        $toAccount = new Account($user2, CurrencyEnum::USD, 200.0);
        $this->setId($toAccount, 2);

        $this->accountsRepository->method('findById')
            ->willReturnMap([
                [1, $fromAccount],
                [2, $toAccount]
            ]);

        $this->accountsRepository->expects($this->exactly(2))
            ->method('updateAccount');

        $this->transactionsRepository->expects($this->once())
            ->method('createTransaction');

        $result = $this->useCase->execute($command);

        $this->assertEquals(400.0, $fromAccount->getBalance());
        $this->assertEquals(300.0, $toAccount->getBalance());
        
        $serialized = $result->jsonSerialize();
        $this->assertCount(2, $serialized);
        $this->assertEquals(400.0, $serialized[0]->jsonSerialize()['balance']);
        $this->assertEquals(300.0, $serialized[1]->jsonSerialize()['balance']);
    }

    private function setId(object $object, int $id): void
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty('id');
        $property->setValue($object, $id);
    }
}
