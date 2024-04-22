<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\Etat;
use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixture extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Génération de 5 livres
        for ($i = 0; $i < 20; $i++) {
            $livre = new Book();
            $livre->setTitle($this->faker->sentence(1));
            $livre->setAuthor($this->faker->sentence(1));
            $livre->setState($this->faker->sentence(1));
            $livre->setDateEmprunt($this->faker->dateTimeThisMonth());
            $livre->setDisponibility($this->faker->boolean());

            // Génération aléatoire d'un état
            $etat = new Etat();
            $etat->setTypeEtat($this->faker->randomElement(['Nouveau', 'Bon', 'Usé']));
            $manager->persist($etat);

            $livre->setEtat($etat);

            // Génération aléatoire d'une catégorie
            $categorie = new Categories();
            $categorie->setName($this->faker->randomElement(['Fiction', 'Non-fiction', 'Science-fiction']));
            $manager->persist($categorie);

            $livre->setCategorie($categorie);

            $manager->persist($livre);
        }

        $manager->flush();
    }
}
