<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory; 
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 10; $i++) {
            $user = new Users();
            $user->setFirstname($this->faker->name())
                ->setLastname($this->faker->name())
                ->setEmail($this->faker->email())
                ->setBirthdate($this->faker->dateTime()) 
                ->setAdress($this->faker->sentence()) 
                ->setCity($this->faker->sentence()) 
                ->setZipCode($this->faker->randomNumber()) 
                ->setPhoneNumber($this->faker->randomNumber())
                ->setPassword('password'); 
                
            $manager->persist($user);
        }

}