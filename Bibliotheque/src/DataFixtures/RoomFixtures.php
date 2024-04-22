<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Room;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RoomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 5; $i++) {
            $salle = new Room();
            $salle->setName($faker->sentence(2));
            $salle->setCapability($faker->numberBetween(5,25));


            $manager->persist($salle);
        }

        $manager->flush();
    }
}
