<?php

namespace App\Controller;
use DateInterval;
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
    #[Route('/', name: 'app_emprunt_livre_index', methods: ['GET'])]
    public function index(Request $request, EmpruntLivreRepository $empruntLivreRepository): Response
    {
        $emprunts = $empruntLivreRepository->findAll();
    
        $books = [];
    
        foreach ($emprunts as $emprunt) {
            $book = $emprunt->getBook();
            if ($book) {
                $books[] = [
                    'emprunt' => $emprunt,
                    'book' => $book,
                ];
            }
        }
    
        return $this->render('votre_template.twig', [
            'books' => $books,
        ]);
    }
    
    #[Route('/nouvel-emprunt/{book_id}', name: 'nouvel_emprunt')]
    public function nouvelEmprunt(Request $request, EntityManagerInterface $entityManager, Book $book): Response
    {
        if (!$book->isDisponibility()) {
            
            return $this->redirectToRoute('page_de_detail', ['id' => $book->getId()]);
        }
    
        
        $emprunt = new EmpruntLivre();
        $emprunt->setBook($book);
        
    
        $entityManager->persist($emprunt);
        $entityManager->flush();
    
        return $this->redirectToRoute('page_de_confirmation', ['id' => $emprunt->getId()]);
    }

    public function demandeExtensionPret(Request $request, EmpruntLivre $emprunt, EntityManagerInterface $entityManager): Response
{
    // Récupérez la date actuelle
    $currentDate = new \DateTime();

    $dateRestitutionPrevue = $emprunt->getDateRestitutionPrevue();

    // Créez un nouvel objet DateTime avec la date modifiée
    $nouvelleDateRestitutionPrevue = new \DateTime();
    $nouvelleDateRestitutionPrevue->setTimestamp($dateRestitutionPrevue->getTimestamp()); // Utilisez le timestamp de la date prévue

    $nouvelleDateRestitutionPrevue->add(new \DateInterval('P6D'));

    $emprunt->setDateRestitutionPrevue($nouvelleDateRestitutionPrevue);

    $entityManager->flush();
    $valeur = 'Valeur de démonstration';

    return $this->render('emprunt_livre/index.html.twig', ['variable' => $valeur]);

}
#[Route('/livre/{id}', name: 'app_livre_detail', methods: ['GET'])]
public function detail(Book $book, EmpruntLivreRepository $empruntLivreRepository): Response
{
    $empruntLivre = $empruntLivreRepository->findOneBy(['book' => $book]);

    return $this->render('livre/detail.html.twig', [
        'book' => $book,
        'emprunt_livre' => $empruntLivre,
    ]);
}
      
}
