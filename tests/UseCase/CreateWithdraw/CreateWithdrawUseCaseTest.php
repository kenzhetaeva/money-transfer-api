<?php

declare(strict_types=1);

namespace App\Tests\UseCase\CreateWithdraw;

use App\Entity\Account;
use App\Entity\User;
use App\Enum\CurrencyEnum;
use App\Exception\AccountNotFoundException;
use App\Exception\InsufficientBalanceException;
use App\Repository\Accounts\AccountsRepositoryInterface;
use App\Repository\Transactions\TransactionsRepositoryInterface;
use App\UseCase\CreateWithdraw\CreateWithdrawCommand;
use App\UseCase\CreateWithdraw\CreateWithdrawUseCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CreateWithdrawUseCaseTest extends TestCase
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

        $this->useCase = new CreateWithdrawUseCase(
            $this->accountsRepository,
            $this->transactionsRepository,
            $this->entityManager
        );
    }

    public function testExecuteThrowsAccountNotFoundExceptionWhenAccountNotFound(): void
    {
        $command = new CreateWithdrawCommand(1, 100.0);

        $this->accountsRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(null);

        $this->expectException(AccountNotFoundException::class);

        $this->useCase->execute($command);
    }

    public function testExecuteThrowsInsufficientBalanceException(): void
    {
        $command = new CreateWithdrawCommand(1, 100.0);

        $account = new Account(new User('User 1', 'u1@example.com'), CurrencyEnum::USD, 50.0);

        $this->accountsRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($account);

        $this->expectException(InsufficientBalanceException::class);

        $this->useCase->execute($command);
    }

    public function testExecuteSuccess(): void
    {
        $command = new CreateWithdrawCommand(1, 100.0);

        $user = new User('User 1', 'u1@example.com');
        $this->setId($user, 1);
        $account = new Account($user, CurrencyEnum::USD, 500.0);
        $this->setId($account, 1);

        $this->accountsRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($account);

        $this->accountsRepository->expects($this->once())
            ->method('updateAccount')
            ->with($account);

        $this->transactionsRepository->expects($this->once())
            ->method('createTransaction');

        $result = $this->useCase->execute($command);

        $this->assertEquals(400.0, $account->getBalance());
        
        $serialized = $result->jsonSerialize();
        $this->assertEquals(400.0, $serialized['balance']);
    }

    private function setId(object $object, int $id): void
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty('id');
        $property->setValue($object, $id);
    }
}
