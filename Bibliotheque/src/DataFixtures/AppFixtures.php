<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un utilisateur admin
        $admin = new Users();
        $admin->setFirstname('Administrateur de la bibliothèque')
            ->setLastname('peter')
            ->setEmail('admin@bibliotheque.fr')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setBirthdate(DateTime::createFromFormat('d/m/Y', '09/09/2000'))
            ->setAdress('zertfgyuhj')
            ->setCity('zertfgyuhj')
            ->setZipCode('3456789')
            ->setPhoneNumber('234567890');

        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'password');
        $admin->setPassword($hashedPassword);

        $manager->persist($admin);

        for ($i = 0; $i < 10; $i++) {
            $user = new Users();
            $user->setFirstname($this->faker->name())
                ->setLastname($this->faker->name())
                ->setEmail($this->faker->email())
                ->setBirthdate($this->faker->dateTime())
                ->setAdress($this->faker->sentence())
                ->setCity($this->faker->sentence())
                ->setZipCode($this->faker->randomNumber())
                ->setPhoneNumber($this->faker->randomNumber());

            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }
        $manager->flush();
    }
}
