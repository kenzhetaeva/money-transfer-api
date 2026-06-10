<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\CurrencyEnum;
use App\Exception\AccountNotFoundException;
use App\Exception\InsufficientBalanceException;
use App\Exception\InvalidAccountsException;
use App\Exception\UserNotFoundException;
use App\UseCase\CreateAccount\CreateAccountCommand;
use App\UseCase\CreateAccount\CreateAccountUseCase;
use App\UseCase\CreateDeposit\CreateDepositCommand;
use App\UseCase\CreateDeposit\CreateDepositUseCase;
use App\UseCase\CreateTransfer\CreateTransferCommand;
use App\UseCase\CreateTransfer\CreateTransferUseCase;
use App\UseCase\CreateWithdraw\CreateWithdrawCommand;
use App\UseCase\CreateWithdraw\CreateWithdrawUseCase;
use App\UseCase\GetAccount\GetAccountCommand;
use App\UseCase\GetAccount\GetAccountUseCase;
use App\Validation\CreateAccountValidator;
use App\Validation\CreateDepositValidator;
use App\Validation\CreateTransferValidator;
use App\Validation\CreateWithdrawValidator;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    public function __construct(
        private CreateAccountUseCase $createAccountUseCase,
        private GetAccountUseCase $getAccountUseCase,
        private CreateDepositUseCase $createDepositUseCase,
        private CreateWithdrawUseCase $createWithdrawUseCase,
        private CreateTransferUseCase $createTransferUseCase,
        private LoggerInterface $logger,
    ) {}

    #[Route('/accounts', methods: ['POST'])]
    public function createAccount(Request $request): Response
    {
        try {
            $requestData = $request->toArray();

            $validator = new CreateAccountValidator($requestData);
            if (!$validator->isValid()) {
                return new JsonResponse(
                    ['errors' => $validator->getErrors()],
                    Response::HTTP_BAD_REQUEST
                );
            }
            $currency = $validator->getValue("currency");
            $currency = CurrencyEnum::from($currency);
            $result = $this->createAccountUseCase->execute(new CreateAccountCommand(
                $validator->getValue("userId"),
                $currency,
                $validator->getValue("balance"),
            ));
            return new JsonResponse($result, Response::HTTP_CREATED);
        } catch (UserNotFoundException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            $this->logger->error('Exception occurred', ['exception' => $e]);
            return new JsonResponse(
                [
                    'error' => 'An error occurred while creating the account',
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/accounts/{id}', methods: ['GET'])]
    public function getAccountById(int $id): JsonResponse
    {
        try {
            $result = $this->getAccountUseCase->execute(new GetAccountCommand($id));
            return new JsonResponse($result, Response::HTTP_OK);
        } catch (AccountNotFoundException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            $this->logger->error('Exception occurred', ['exception' => $e]);
            return new JsonResponse(
                [
                    'error' => 'An error occurred while retrieving account',
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/accounts/{id}/deposit', methods: ['POST'])]
    public function createDeposit(int $id, Request $request): JsonResponse
    {
        try {
            $requestData = $request->toArray();

            $validator = new CreateDepositValidator($requestData);
            if (!$validator->isValid()) {
                return new JsonResponse(
                    ['errors' => $validator->getErrors()],
                    Response::HTTP_BAD_REQUEST
                );
            }
            $result = $this->createDepositUseCase->execute(
                new CreateDepositCommand(
                    $id,
                    $validator->getValue("amount"),
                )
            );
            return new JsonResponse($result, Response::HTTP_OK);
        } catch (AccountNotFoundException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            $this->logger->error('Exception occurred', ['exception' => $e]);
            return new JsonResponse(
                [
                    'error' => 'An error occurred while creating the deposit',
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/accounts/{id}/withdraw', methods: ['POST'])]
    public function createWithdraw(int $id, Request $request): JsonResponse
    {
        try {
            $requestData = $request->toArray();

            $validator = new CreateWithdrawValidator($requestData);
            if (!$validator->isValid()) {
                return new JsonResponse(
                    ['errors' => $validator->getErrors()],
                    Response::HTTP_BAD_REQUEST
                );
            }
            $result = $this->createWithdrawUseCase->execute(
                new CreateWithdrawCommand(
                    $id,
                    $validator->getValue("amount"),
                )
            );
            return new JsonResponse($result, Response::HTTP_OK);
        } catch (AccountNotFoundException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        } catch (InsufficientBalanceException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        } catch (Exception $e) {
            $this->logger->error('Exception occurred', ['exception' => $e]);
            return new JsonResponse(
                [
                    'error' => 'An error occurred while creating the withdraw',
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/transfer', methods: ['POST'])]
    public function createTransfer(Request $request): JsonResponse
    {
        try {
            $requestData = $request->toArray();

            $validator = new CreateTransferValidator($requestData);
            if (!$validator->isValid()) {
                return new JsonResponse(
                    ['errors' => $validator->getErrors()],
                    Response::HTTP_BAD_REQUEST
                );
            }
            $result = $this->createTransferUseCase->execute(
                new CreateTransferCommand(
                    $validator->getValue("amount"),
                    $validator->getValue("fromAccountId"),
                    $validator->getValue("toAccountId"),
                )
            );
            return new JsonResponse($result, Response::HTTP_OK);
        } catch (AccountNotFoundException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        } catch (InsufficientBalanceException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        } catch (InvalidAccountsException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        } catch (Exception $e) {
            $this->logger->error('Exception occurred', ['exception' => $e]);
            return new JsonResponse(
                [
                    'error' => 'An error occurred while creating the transfer',
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
