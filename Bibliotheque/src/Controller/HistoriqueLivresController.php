<?php

namespace App\Controller;

use App\Repository\EmpruntLivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class HistoriqueLivresController extends AbstractController
{
    private $empruntLivreRepository;

    public function __construct(EmpruntLivreRepository $empruntLivreRepository)
    {
        $this->empruntLivreRepository = $empruntLivreRepository;
    }

    #[Route('/historique/livres', name: 'app_historique_livres')]
    public function listEmpruntsLivres(Security $security): Response
    {
        if (!$security->getUser()) {
            throw new AccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $user = $this->getUser();

        $emprunts = $this->empruntLivreRepository->findBy(['user' => $user]);

        if (empty($emprunts)) {
            return $this->render('historique_livres/index.html.twig', [
                'controller_name' => 'HistoriqueLivresController',
                'message' => "Vous n'avez pas encore effectué d'emprunt pour le moment."
            ]);
        }

        $empruntsDetails = [];

        foreach ($emprunts as $emprunt) {
            $book = $emprunt->getBook();
            $dateEmprunt = $emprunt->getDateEmprunt();

            $empruntsDetails[] = [
                'titre_livre' => $book->getTitle(),
                'auteur_livre' => $book->getAuthor(),
                'resume_livre' => $book->getResume(),
                'etat_livre' => $book->getState(),
                'date_emprunt' => $dateEmprunt->format('Y-m-d'), 
            ];
        }

        return $this->render('historique_livres/index.html.twig', [
            'controller_name' => 'HistoriqueLivresController',
            'empruntsDetails' => $empruntsDetails,
            'prenomUser' => $user->getFirstname()
        ]);
    }
}
