<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\AccountNotFoundException;
use App\UseCase\GetTransactions\GetTransactionsCommand;
use App\UseCase\GetTransactions\GetTransactionsUseCase;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TransactionController extends AbstractController
{
    public function __construct(
        private GetTransactionsUseCase $getTransactionsUseCase,
        private LoggerInterface $logger,
    ) {}

    #[Route('/accounts/{id}/transactions', methods: ['GET'])]
    public function getTransactions(int $id): JsonResponse
    {
        try {
            $result = $this->getTransactionsUseCase->execute(new GetTransactionsCommand($id));
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
}
