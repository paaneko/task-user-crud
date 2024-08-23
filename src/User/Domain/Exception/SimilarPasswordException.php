<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

final class SimilarPasswordException extends DomainException
{
    public function __construct()
    {
        parent::__construct('New password cannot be same as old password');
    }
}
