<?php

declare(strict_types=1);

namespace App\User\Application\Service;

use App\User\Application\Dto\AuthUserDto;
use App\User\Domain\Exception\JwtDecodingFailedException;
use App\User\Domain\ValueObject\Id;
use App\User\Domain\ValueObject\Login;
use App\User\Domain\ValueObject\Role;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class SimpleJWTService implements JWTServiceInterface
{
    public function __construct(private string $secretKey)
    {
    }

    public function encode(Id $id, Login $login, Role $role): string
    {
        $payload = [
            'id' => $id->getValue(),
            'login' => $login->getValue(),
            'role' => $role->getValue(),
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function decode(string $jwt): AuthUserDto
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));

            return new AuthUserDto(
                (string) $decoded->id,
                (string) $decoded->login,
                (string) $decoded->role
            );
        } catch (\Exception $e) {
            throw new JwtDecodingFailedException();
        }
    }
}
