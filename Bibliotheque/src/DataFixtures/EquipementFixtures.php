<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Equipements;
use App\Repository\RoomRepository;
use Doctrine\Persistence\ObjectManager;
use App\Repository\EquipementsRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EquipementFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private RoomRepository $salleRepo,private EquipementsRepository $equipementRepo )
    {

    } 
    public function load(ObjectManager $manager): void
    {

        $equipements =["Wi-Fi","Projecteur", "Tableau", "Prises électriques", "Télévision", "Climatisation"];
        $equips=[];
        foreach ($equipements as $nom) {
            $equipement = new Equipements();
            $equipement->setNom($nom);
            $manager->persist($equipement);
            $equips[]= $equipement;
        }


        $salles = $this->salleRepo->findAll();

        foreach($salles as $salle){
            for($i = 0; $i < mt_rand(1, 4); $i++){
                $salle->addEquipement($equips[mt_rand(0, count($equips) - 1)]
            );
            }
        }


        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            RoomFixtures::class
        ];
    }
}
