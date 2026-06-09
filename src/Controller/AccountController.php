<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\CurrencyEnum;
use App\Exception\AccountNotFoundException;
use App\Exception\UserNotFoundException;
use App\UseCase\CreateAccount\CreateAccountCommand;
use App\UseCase\CreateAccount\CreateAccountUseCase;
use App\UseCase\GetAccount\GetAccountCommand;
use App\UseCase\GetAccount\GetAccountUseCase;
use App\Validation\CreateAccountValidator;
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
                [
                    'error' => 'User not found',
                    'message' => $e->getMessage(),
                ],
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


    #[Route('/accounts', methods: ['GET'])]
    public function getAccountById(Request $request): JsonResponse
    {
        try {
            $accountId = $request->query->get('id');

            if (!$accountId) {
                return new JsonResponse(
                    ['error' => 'Missing required parameter: id'],
                    Response::HTTP_BAD_REQUEST
                );
            }
            if (!is_numeric($accountId)) {
                return new JsonResponse(
                    ['error' => 'Parameter \'id\' must be numeric'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $result = $this->getAccountUseCase->execute(new GetAccountCommand((int)$accountId));
            return new JsonResponse($result, Response::HTTP_OK);
        } catch (AccountNotFoundException $e) {
            return new JsonResponse(
                [
                    'error' => 'Account not found',
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            $this->logger->error('Exception occurred', ['exception' => $e]);
            return new JsonResponse(
                [
                    'error' => 'An error occurred while creating the user',
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
