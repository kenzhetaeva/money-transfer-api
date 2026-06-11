<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\AccountNotFoundException;
use App\Exception\InsufficientBalanceException;
use App\Exception\InvalidAccountsException;
use App\UseCase\CreateDeposit\CreateDepositCommand;
use App\UseCase\CreateDeposit\CreateDepositUseCase;
use App\UseCase\CreateTransfer\CreateTransferCommand;
use App\UseCase\CreateTransfer\CreateTransferUseCase;
use App\UseCase\CreateWithdraw\CreateWithdrawCommand;
use App\UseCase\CreateWithdraw\CreateWithdrawUseCase;
use App\UseCase\GetTransactions\GetTransactionsCommand;
use App\UseCase\GetTransactions\GetTransactionsUseCase;
use App\Validation\CreateDepositValidator;
use App\Validation\CreateTransferValidator;
use App\Validation\CreateWithdrawValidator;
use App\Validation\GetTransactionsValidator;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TransactionController extends AbstractController
{
    public function __construct(
        private GetTransactionsUseCase $getTransactionsUseCase,
        private CreateDepositUseCase $createDepositUseCase,
        private CreateWithdrawUseCase $createWithdrawUseCase,
        private CreateTransferUseCase $createTransferUseCase,
        private LoggerInterface $logger,
    ) {}

    #[Route('/accounts/{id}/transactions', methods: ['GET'])]
    public function getTransactions(int $id, Request $request): JsonResponse
    {
        try {
            $requestData = [
                'perPage' => (int) $request->query->get('perPage', 20),
                'page' => (int) $request->query->get('page', 1),
            ];

            $validator = new GetTransactionsValidator($requestData);
            if (!$validator->isValid()) {
                return new JsonResponse(
                    ['errors' => $validator->getErrors()],
                    Response::HTTP_BAD_REQUEST
                );
            }
            $result = $this->getTransactionsUseCase->execute(
                new GetTransactionsCommand(
                    $id,
                    $validator->getValue("perPage"),
                    $validator->getValue("page"),
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
                    'error' => 'An error occurred while retrieving transactions',
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
