<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Id;
use App\User\Domain\ValueObject\Login;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class MysqlUserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    /** @psalm-suppress UnusedParam*/
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findById(Id $id): ?User
    {
        return $this->find($id->getValue());
    }

    public function findByLogin(Login $login): ?User
    {
        return $this->findOneBy(['login' => $login->getValue()]);
    }

    public function hasByLogin(Login $login): bool
    {
        $user = $this->findOneBy(['login' => $login->getValue()]);

        return null !== $user;
    }

    public function hasById(Id $id): bool
    {
        $user = $this->find($id->getValue());

        return null !== $user;
    }

    public function delete(Id $id): void
    {
        /** @var User $user */
        $user = $this->find($id->getValue());

        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }
}
