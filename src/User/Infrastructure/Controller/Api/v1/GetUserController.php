<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Controller\Api\v1;

use App\User\Application\Dto\AuthUserDto;
use App\User\Application\UseCase\GetUser\GetUserCommand;
use App\User\Application\UseCase\GetUser\GetUserCommandHandler;
use App\User\Domain\Exception\UnauthorizedAccessException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class GetUserController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private GetUserCommandHandler $commandHandler
    ) {
    }

    #[Route('v1/api/users', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $parameters = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        /** @var AuthUserDto|null $authUserDto */
        $authUserDto = $request->attributes->get('authUser');

        if (null === $authUserDto) {
            throw new UnauthorizedAccessException();
        }

        $getUserCommand = new GetUserCommand(
            $authUserDto,
            $parameters['id']
        );

        $violations = $this->validator->validate($getUserCommand);

        if (count($violations) > 0) {
            throw new ValidationFailedException($getUserCommand, $violations);
        }

        $user = $this->commandHandler->handle($getUserCommand);

        return $this->json([
            'login' => $user->getLogin()->getValue(),
            'phone' => $user->getPhone()->getValue(),
            'pass' => $user->getHashedPassword(),
        ], Response::HTTP_OK);
    }
}
