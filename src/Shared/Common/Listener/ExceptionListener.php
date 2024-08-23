<?php

namespace App\Shared\Common\Listener;

use App\User\Domain\Exception\DomainException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final readonly class ExceptionListener
{
    public function __construct(
        private string $env
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ('dev' === $this->env) {
            return;
        }
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationFailedException) {
            $event->setResponse(
                new JsonResponse(
                    ['message' => $this->formatValidationErrors($exception->getViolations())],
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    []
                )
            );
        } elseif ($exception instanceof DomainException) {
            $event->setResponse(
                new JsonResponse(
                    ['message' => $exception->getMessage()],
                    Response::HTTP_BAD_REQUEST
                )
            );
        } elseif ($exception instanceof InvalidArgumentException) {
            $event->setResponse(
                new JsonResponse(
                    ['message' => $exception->getMessage()],
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    []
                )
            );
        } else {
            $data = [
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
            ];
            $event->setResponse(new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    private function formatValidationErrors(ConstraintViolationList $violationList): array
    {
        $errors = [];
        foreach ($violationList as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}