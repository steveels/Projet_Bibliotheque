<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\Users;
use App\Entity\Commentaires;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CommentairesFixtures extends Fixture
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
           $commentaire = new Commentaires();

           $commentaire->setDateAjout($this->faker->dateTimeThisMonth())
                ->setContent($this->faker->sentence(1));

           $utilisateur = $this->faker->randomElement($utilisateurs);
           $commentaire->setComUser($utilisateur);

           $livre = $this->faker->randomElement($livres);
           $commentaire->setBook($livre);

           $manager->persist($commentaire);
        }

        $manager->flush();
    }
}
