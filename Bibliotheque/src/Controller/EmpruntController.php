<?php

namespace App\Controller;

use App\Entity\EmpruntLivre;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EmpruntLivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmpruntController extends AbstractController
{
    #[Route('/emprunt', name: 'app_emprunt')]
    public function index(): Response
    {
        return $this->render('emprunt/index.html.twig', [
            'controller_name' => 'EmpruntController',
        ]);
    }

    public function empruntlivre(EmpruntLivreRepository $empruntLivreRepository): Response
    {
        // Récupérer les emprunts de livres depuis le repository
        $emprunts = $empruntLivreRepository->findAll();

        // Passer les données au template Twig
        return $this->render('emprunt.html.twig', [
            'emprunts' => $emprunts,
        ]);
    }
    
    #[Route('/nouvel-emprunt', name: 'nouvel_emprunt')]
    public function nouvelEmprunt(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emprunt = new EmpruntLivre();
        
        // Récupérer la date d'emprunt depuis le formulaire
        $dateEmprunt = new \DateTime($request->request->get('date_emprunt'));
        $emprunt->setDateEmprunt($dateEmprunt);
        
        // Calculer la date de retour (6 jours après la date d'emprunt)
        $dateRetour = clone $dateEmprunt;
        $dateRetour->modify('+6 days');
        $emprunt->setDateRetour($dateRetour);

        

        // Persister et flusher l'emprunt dans la base de données
        $entityManager->persist($emprunt);
        $entityManager->flush();

        // Rediriger vers une page de confirmation ou une autre page
        return $this->redirectToRoute('page_de_confirmation');
    }

}
