<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Controller\Api\v1;

use App\User\Application\Dto\AuthUserDto;
use App\User\Application\UseCase\DeleteUser\DeleteUserCommand;
use App\User\Application\UseCase\DeleteUser\DeleteUserCommandHandler;
use App\User\Domain\Exception\UnauthorizedAccessException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class DeleteUserController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private DeleteUserCommandHandler $commandHandler
    ) {
    }

    #[Route('v1/api/users', methods: ['DELETE'])]
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

        $deleteUserCommand = new DeleteUserCommand(
            $authUserDto,
            $parameters['id'],
        );

        $violations = $this->validator->validate($deleteUserCommand);

        if (count($violations) > 0) {
            throw new ValidationFailedException($deleteUserCommand, $violations);
        }

        $this->commandHandler->handle($deleteUserCommand);

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
