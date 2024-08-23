<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Controller\Api\v1;

use App\User\Application\Dto\AuthUserDto;
use App\User\Application\UseCase\RegisterUser\RegisterUserCommand;
use App\User\Application\UseCase\RegisterUser\RegisterUserCommandHandler;
use App\User\Domain\Exception\UnauthorizedAccessException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RegisterUserController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private RegisterUserCommandHandler $commandHandler
    ) {
    }

    #[Route('v1/api/users', methods: ['POST'])]
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

        $registerUserCommand = new RegisterUserCommand(
            $parameters['id'],
            $parameters['login'],
            $parameters['phone'],
            $parameters['pass']
        );

        $violations = $this->validator->validate($registerUserCommand);

        if (count($violations) > 0) {
            throw new ValidationFailedException($registerUserCommand, $violations);
        }

        $registeredUser = $this->commandHandler->handle($registerUserCommand);

        return $this->json([
            'id' => $registeredUser->getId()->getValue(),
            'login' => $registeredUser->getLogin()->getValue(),
            'phone' => $registeredUser->getPhone()->getValue(),
            'pass' => $registeredUser->getHashedPassword(),
        ],
            Response::HTTP_CREATED);
    }
}
