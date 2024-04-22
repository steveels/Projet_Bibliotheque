<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\EmpruntLivre;
use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class EmpruntFixture extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $utilisateurs = $manager->getRepository(Users::class)->findAll();
        $livres = $manager->getRepository(Book::class)->findAll();


        for ($i = 0; $i < 20; $i++) {
           $emprunt = new EmpruntLivre();

           $emprunt->setDateEmprunt($this->faker->dateTimeThisMonth())
           ->setDateRestitution($this->faker->dateTimeThisMonth())
           ->setDateRestituionEffective($this->faker->dateTimeThisMonth());

           $utilisateur = $this->faker->randomElement($utilisateurs);
           $emprunt->setUser($utilisateur);

           $livre = $this->faker->randomElement($livres);
           $emprunt->setBook($livre);

           $manager->persist($emprunt);
        }

        $manager->flush();
    }
}
