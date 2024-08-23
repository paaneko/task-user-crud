<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

final class JwtDecodingFailedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('JWT decoding failed');
    }
}
