<?php

namespace App\User\Domain\Exception;

class SimilarPasswordException extends DomainException
{
    public function __construct()
    {
        parent::__construct('New password cannot be same as old password');
    }
}