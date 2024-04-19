<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Book; 
use App\Entity\Categories;
use App\Entity\Reservation;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    private $entityManager;
    private $bookRepository;
    private $etat;
    private $categories;

    public function __construct(EntityManagerInterface $entityManager, BookRepository $bookRepository, Etat $etat, Categories $categories)
    {
        $this->entityManager = $entityManager;
        $this->bookRepository = $bookRepository;
        $this->etat = $etat;
        $this->categories = $categories;
    }

    #[Route('/book', name: 'app_book')]
    public function index(Request $request): Response
    {
        // Récupère la liste des livres depuis la base de données
        $livres = $this->bookRepository->findAll();
        
        // Rend la vue Twig en passant les données des livres
        return $this->render('livre/index.html.twig', [
            'livres' => $livres,
        ]);
    }

    /**
     * @Route("/livres/{id}", name="detail_livre")
     */
    public function show(int $id): Response
    {
        // Récupère les détails du livre spécifié par son ID
        $livre = $this->bookRepository->find($id);
        
        // Rend la vue Twig en passant les détails du livre
        return $this->render('livre/detail.html.twig', [
            'livre' => $livre,
        ]);
    }

   
}
    

