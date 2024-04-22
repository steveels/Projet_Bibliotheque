<?php

namespace App\Controller;

use App\Repository\EmpruntLivreRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HistoriqueLivresController extends AbstractController
{
    #[Route('/historique/livres', name: 'app_historique_livres')]
    #[IsGranted('ROLE_USER')]
    public function index(EmpruntLivreRepository $EmpruntLivreRepository, Security $security): Response
    {

        $user = $this->getUser();

        if(!$security->getUser()){
            return $this->redirectToRoute('app_login');
        }
        
        $emprunts = $EmpruntLivreRepository->findBy(['user' => $user]);

        return $this->render('historique_livres/index.html.twig', [
            'controller_name' => 'HistoriqueLivresController',
            'emprunts' => $emprunts
        ]);
    }
}
