<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HistoriqueSalleController extends AbstractController
{
    #[Route('/historique/salle', name: 'app_historique_salle')]
    #[IsGranted('ROLE_USER')]
    public function index(ReservationRepository $ReservationRepository, Security $security): Response
    {
        $user = $this->getUser();


        if(!$security->getUser()){
            return $this->redirectToRoute('app_login');
        }
        
        $reservation = $ReservationRepository->findBy(['user' => $user]);



        return $this->render('historique_salle/index.html.twig', [
            'controller_name' => 'HistoriqueSalleController',
            'reservations' => $reservation
        ]);
    }
}
