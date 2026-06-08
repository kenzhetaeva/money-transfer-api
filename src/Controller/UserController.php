<?php

declare(strict_types=1);

namespace App\Controller;

use App\UseCase\User\CreateUserCommand;
use App\UseCase\User\CreateUserUseCase;
use App\Validation\CreateUserValidator;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Annotations as OA;
use OpenApi\Annotations\Post;

final class UserController extends AbstractController
{
    public function __construct(
        private CreateUserUseCase $useCase,
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
            $this->useCase->execute(new CreateUserCommand(
                $validator->getValue("name"),
                $validator->getValue("email")
            ));
            return new JsonResponse(
                ['message' => 'User created successfully'],
                Response::HTTP_CREATED
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
