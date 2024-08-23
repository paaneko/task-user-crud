<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\Service;

use App\Tests\Builder\UserBuilder;
use App\User\Application\Service\SimpleJWTService;
use App\User\Domain\Exception\JwtDecodingFailedException;
use App\User\Domain\ValueObject\Role;
use PHPUnit\Framework\TestCase;

final class SimpleJWTServiceTest extends TestCase
{
    public function testCanEncodeAndDecode(): void
    {
        $jwtService = new SimpleJWTService('secret');
        $user = (new UserBuilder())->build();

        $encodedToken = $jwtService->encode($user->getId(), $user->getLogin(), $role = Role::testAdmin());

        $authUserDto = $jwtService->decode($encodedToken);

        $this->assertEquals($user->getId()->getValue(), $authUserDto->userId);
        $this->assertEquals($user->getLogin()->getValue(), $authUserDto->login);
        $this->assertEquals($role->getValue(), $authUserDto->role);
    }

    public function testThrowsJwtDecodingFailedException(): void
    {
        $jwtService = new SimpleJWTService('secret');

        $this->expectException(JwtDecodingFailedException::class);

        $jwtService->decode('not-valid-jwt-token');
    }
}
