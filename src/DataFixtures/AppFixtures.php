<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $gender = $faker->randomElement(['men', 'women']);
        $people = rand(1, 99);
        $user = new User();
        $user->setEmail('fiorella@boxydev.com');
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $user->setFirstname('Fiorella');
        $user->setUsername('fiorella');
        $user->setAvatar('https://randomuser.me/api/portraits/'.$gender.'/'.$people.'.jpg');
        $user->setBornAt(new \DateTimeImmutable('2019-12-31'));
        $user->setBiography('Une petite fille incroyable.');
        $manager->persist($user);

        for ($i = 1; $i <= 10; $i++) {
            $post = new Post();
            $post->setContent($faker->text());
            $post->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-60 days')));
            $post->setCreator($user);
            $manager->persist($post);
        }

        $manager->flush();
    }
}
