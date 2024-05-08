<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Book; 
use App\Entity\Categories;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
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
        $user = $this->getUser();
        $hasActiveSubscription = false;

        if ($user instanceof UserInterface) {
            $subscription = $user->getSubscription();
            $hasActiveSubscription = $subscription && $subscription->isActive();
        }
        // Rend la vue Twig en passant les données des livres
        return $this->render('livre/index.html.twig', [
            'livres' => $livres,
            'hasActiveSubscription' => $hasActiveSubscription,
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
            'book' => $livre,
        ]);
    }
 /**
     * @Route("/livre/{id}", name="livre_detail")
     */
    public function detail(int $id, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);
        
    
        return $this->render('livre/detail.html.twig', [
            'book' => $book,
        ]);
    }
    public function list(): Response
    {
        // Supposons que tu as une liste de livres, $livres
        $livreId = 123; // ID d'un livre spécifique, à titre d'exemple

        // Générer l'URL pour afficher les détails du livre avec l'ID spécifique
        $url = $this->generateUrl('detail_livre', ['id' => $livreId]);

        // Utilise cette URL comme tu en as besoin dans ton contrôleur
        // Par exemple, tu pourrais la passer à une vue Twig pour créer un lien vers les détails du livre

        // Envoie une réponse (ici, c'est juste un exemple)
        return $this->render('book/list.html.twig', [
            'url' => $url,
        ]);
    }
}
    

