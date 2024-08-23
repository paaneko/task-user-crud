<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Controller\Api\v1;

use App\User\Application\Dto\AuthUserDto;
use App\User\Application\UseCase\PutUser\PutUserCommand;
use App\User\Application\UseCase\PutUser\PutUserCommandHandler;
use App\User\Domain\Exception\UnauthorizedAccessException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class PutUserController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private PutUserCommandHandler $commandHandler
    ) {
    }

    #[Route('v1/api/users', methods: ['PUT'])]
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

        $putUserCommand = new PutUserCommand(
            $authUserDto,
            $parameters['phone'],
            $parameters['login'],
            $parameters['pass']
        );

        $violations = $this->validator->validate($putUserCommand);

        if (count($violations) > 0) {
            throw new ValidationFailedException($putUserCommand, $violations);
        }

        $userId = $this->commandHandler->handle($putUserCommand);

        return $this->json(['id' => $userId->getValue()], Response::HTTP_OK);
    }
}
