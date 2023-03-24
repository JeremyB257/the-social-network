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
        $users = [];

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
        $users[] = $user;
        
        for ($i = 2; $i <= 10; $i++) {
            $gender = $faker->randomElement(['men', 'women']);
            $people = rand(1, 99);
            $user = new User();
            $user->setEmail($faker->email());
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            $user->setFirstname($faker->firstName($gender === 'men' ? 'male' : 'female'));
            $user->setUsername($faker->userName());
            $user->setAvatar('https://randomuser.me/api/portraits/'.$gender.'/'.$people.'.jpg');
            $user->setBornAt(\DateTimeImmutable::createFromMutable($faker->dateTime((date('Y') - 18).'-12-31')));
            $user->setBiography($faker->text());
            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 1; $i <= 10; $i++) {
            $post = new Post();
            $post->setContent($faker->text());
            $post->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-60 days')));
            $post->setCreator($faker->randomElement($users));
            $manager->persist($post);
        }

        $manager->flush();
    }
}
