<?php

namespace App\Controller;




use App\Repository\RoomRepository;
use App\Repository\EquipementsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GestionSalleController extends AbstractController
{
    

    #[Route('/gestion/salle', name: 'app_gestion_salle')]
    public function index(RoomRepository $room, EquipementsRepository $equipement, Request $request ): Response
    {
        
        $room = $room->findAll();
        $user = $this->getUser();
        $hasActiveSubscription = false;

        if ($user instanceof UserInterface) {
            $subscription = $user->getSubscription();
            $hasActiveSubscription = $subscription && $subscription->isActive();
        }

        return $this->render('gestion_salle/index.html.twig', [
            'rooms' => $room, 
            'hasActiveSubscription' => $hasActiveSubscription,
            
             
        ]);
    }
}
