<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\EmpruntLivre;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EmpruntLivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmpruntController extends AbstractController
{
    #[Route('/emprunt', name: 'app_emprunt')]
    public function index(BookRepository $empruntLivreRepository): Response
    {
        // Récupérer les emprunts de livres depuis le repository
        $emprunts = $empruntLivreRepository->findAll();

        // Passer les données au template Twig
        return $this->render('emprunt/index.html.twig', [
            'emprunts' => $emprunts,
        ]);
        
       
    }

   
    
    #[Route('/nouvel-emprunt/{book_id}', name: 'nouvel_emprunt')]
    public function nouvelEmprunt(Request $request, EntityManagerInterface $entityManager, Book $book): Response
    {
        // Vérifier si le livre est disponible
        if (!$book->isDisponibility()) {
            
            return $this->redirectToRoute('page_de_detail', ['id' => $book->getId()]);
        }
    
        
        $emprunt = new EmpruntLivre();
        $emprunt->setBook($book);
        
    
        // Enregistrer l'emprunt dans la base de données
        $entityManager->persist($emprunt);
        $entityManager->flush();
    
        // Rediriger vers la page de confirmation d'emprunt
        return $this->redirectToRoute('page_de_confirmation', ['id' => $emprunt->getId()]);
    }

}
