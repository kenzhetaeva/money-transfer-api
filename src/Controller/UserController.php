<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\DuplicatedEmailException;
use App\Exception\UserNotFoundException;
use App\UseCase\CreateUser\CreateUserCommand;
use App\UseCase\CreateUser\CreateUserUseCase;
use App\UseCase\GetUser\GetUserCommand;
use App\UseCase\GetUser\GetUserUseCase;
use App\Validation\CreateUserValidator;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    public function __construct(
        private CreateUserUseCase $createUserUseCase,
        private GetUserUseCase $getUserUseCase,
        private LoggerInterface $logger,
    ) {}

    #[Route('/users', methods: ['POST'])]
    public function createUser(Request $request): Response
    {
        try {
            $requestData = $request->toArray();

            $validator = new CreateUserValidator($requestData);
            if (!$validator->isValid()) {
                return new JsonResponse(
                    ['errors' => $validator->getErrors()],
                    Response::HTTP_BAD_REQUEST
                );
            }
            $result = $this->createUserUseCase->execute(new CreateUserCommand(
                $validator->getValue("name"),
                $validator->getValue("email")
            ));
            return new JsonResponse($result, Response::HTTP_CREATED);
        } catch (DuplicatedEmailException $e) {
            return new JsonResponse(
                [
                    'error' => 'User duplicated by email',
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_CONFLICT
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


    #[Route('/user', methods: ['GET'])]
    public function getUserById(Request $request): JsonResponse
    {
        try {
            $userId = $request->query->get('id');

            if (!$userId) {
                return new JsonResponse(
                    ['error' => 'Missing required parameter: id'],
                    Response::HTTP_BAD_REQUEST
                );
            }
            if (!is_numeric($userId)) {
                return new JsonResponse(
                    ['error' => 'Parameter \'id\' must be numeric'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $result = $this->getUserUseCase->execute(new GetUserCommand((int)$userId));
            return new JsonResponse($result, Response::HTTP_OK);
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
                    'error' => 'An error occurred while creating the user',
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
