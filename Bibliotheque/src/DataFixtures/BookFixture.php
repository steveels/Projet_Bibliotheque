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
        $image = [
            'https://boutique.annabac.com/system/product_pictures/data/009/953/889/large/9782401078499-001-X.jpg?1687516306',
            
        ];
        // Génération de 5 livres
        for ($i = 0; $i < 20; $i++) {
            $livre = new Book();
            $livre->setTitle($this->faker->sentence(1));
            $livre->setAuthor($this->faker->sentence(1));
            $livre->setState($this->faker->sentence(1));
            $livre->setDateEmprunt($this->faker->dateTimeThisMonth());
            $livre->setDisponibility($this->faker->boolean());
            
            $livre->setImage($this->faker->randomElement($image));
            // Génération aléatoire d'un état
            $etat = new Etat();
            $etat->setTypeEtat($this->faker->randomElement(['excellent',
            'correct','moyen','mauvais']));
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
