<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Fixture;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Id;
use App\User\Domain\ValueObject\Login;
use App\User\Domain\ValueObject\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $adminUser = new User(
            new Id('111'),
            new Login('admin'),
            new Phone((string) $faker->numberBetween(1, 999999)),
            $faker->sha256()
        );
        $manager->persist($adminUser);

        $userUser = new User(
            new Id('123'),
            new Login('user'),
            new Phone((string) $faker->numberBetween(1, 999999)),
            $faker->sha256()
        );
        $manager->persist($userUser);

        $testUser = new User(
            new Id('1'),
            new Login('test'),
            new Phone('123123'),
            'testPassword'
        );
        $manager->persist($testUser);

        for ($i = 0; $i < 20; ++$i) {
            $user = new User(
                new Id((string) $faker->numberBetween(999, 999999)),
                new Login($faker->bothify('####????')),
                new Phone((string) $faker->numberBetween(1, 999999)),
                $faker->sha256()
            );
            $manager->persist($user);
        }

        $manager->flush();
    }
}
