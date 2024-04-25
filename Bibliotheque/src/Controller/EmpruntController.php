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
        // Récupérer tous les emprunts
        $emprunts = $empruntLivreRepository->findAll();
    
        // Créer un tableau pour stocker les livres associés à chaque emprunt
        $books = [];
    
        // Pour chaque emprunt, récupérer le livre associé
        foreach ($emprunts as $emprunt) {
            $book = $emprunt->getBook();
            // Si le livre est trouvé, l'ajouter au tableau avec l'emprunt correspondant
            if ($book) {
                $books[] = [
                    'emprunt' => $emprunt,
                    'book' => $book,
                ];
            }
        }
    
        // Retourner une réponse en utilisant un modèle Twig avec les données récupérées
        return $this->render('votre_template.twig', [
            'books' => $books,
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

    public function demandeExtensionPret(Request $request, EmpruntLivre $emprunt, EntityManagerInterface $entityManager): Response
{
    // Récupérez la date actuelle
    $currentDate = new \DateTime();

    // Récupérez la date de restitution prévue de l'emprunt
    $dateRestitutionPrevue = $emprunt->getDateRestitutionPrevue();

    // Créez un nouvel objet DateTime avec la date modifiée
    $nouvelleDateRestitutionPrevue = new \DateTime();
    $nouvelleDateRestitutionPrevue->setTimestamp($dateRestitutionPrevue->getTimestamp()); // Utilisez le timestamp de la date prévue

    // Ajoutez un intervalle de 6 jours à la date actuelle
    $nouvelleDateRestitutionPrevue->add(new \DateInterval('P6D'));

    // Mettez à jour la date de restitution prévue de l'emprunt
    $emprunt->setDateRestitutionPrevue($nouvelleDateRestitutionPrevue);

    // Enregistrez les modifications dans la base de données
    $entityManager->flush();
    $valeur = 'Valeur de démonstration';

    // Redirigez l'utilisateur vers une page de confirmation ou une autre page appropriée
    return $this->render('emprunt_livre/index.html.twig', ['variable' => $valeur]);

}
#[Route('/livre/{id}', name: 'app_livre_detail', methods: ['GET'])]
public function detail(Book $book, EmpruntLivreRepository $empruntLivreRepository): Response
{
    // Récupérer les détails de l'emprunt du livre
    $empruntLivre = $empruntLivreRepository->findOneBy(['book' => $book]);

    return $this->render('livre/detail.html.twig', [
        'book' => $book,
        'emprunt_livre' => $empruntLivre,
    ]);
}
      
}
