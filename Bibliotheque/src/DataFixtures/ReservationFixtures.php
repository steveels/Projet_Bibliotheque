<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Room;
use App\Entity\Users;
use App\Entity\Reservation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ReservationFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $utilisateurs = $manager->getRepository(Users::class)->findAll();
        $salles = $manager->getRepository(Room::class)->findAll();


        for ($i = 0; $i < 20; $i++) {
           $reservation = new Reservation();

           $reservation->setDateDebut($this->faker->dateTime())
           ->setDateFin($this->faker->dateTime());

           $utilisateur = $this->faker->randomElement($utilisateurs);
           $reservation->setUser($utilisateur);

           $salle = $this->faker->randomElement($salles);
           $reservation->setRoom($salle);

           $manager->persist($reservation);
        }

        $manager->flush();
    }
}
