<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\EmpruntLivre;
use App\Form\EmpruntLivreType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EmpruntLivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/emprunt/livre')]
class EmpruntLivreController extends AbstractController
{
    #[Route('/', name: 'app_emprunt_livre_index', methods: ['GET'])]
public function index(EmpruntLivreRepository $empruntLivreRepository): Response
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

    return $this->render('emprunt_livre/index.html.twig', [
        'emprunt_livres' => $books,
    ]);
}
    #[Route('/new', name: 'app_emprunt_livre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $empruntLivre = new EmpruntLivre();
        $form = $this->createForm(EmpruntLivreType::class, $empruntLivre);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si le livre existe
            $livre = $empruntLivre->getBook();
            if (!$livre) {
                // Livre non trouvé, afficher un message d'erreur
                $this->addFlash('error', 'Le livre sélectionné n\'existe pas.');
                return $this->redirectToRoute('app_emprunt_livre_new');
            }
    
            // Vérifier la disponibilité du livre
            if ($livre->isDisponibility()) {
                // Mettre à jour la disponibilité du livre et enregistrer l'emprunt
                $livre->setDisponibility(false);
                $empruntLivre->setDateEmprunt(new \DateTime()); // Enregistrer la date d'emprunt actuelle
                $entityManager->persist($empruntLivre);
                $entityManager->flush();
    
                // Rediriger vers la liste des emprunts avec un message de succès
                $this->addFlash('success', 'Emprunt créé avec succès !');
                return $this->redirectToRoute('app_emprunt_livre_index');
            } else {
                // Afficher un message d'erreur si le livre n'est pas disponible
                $this->addFlash('error', 'Ce livre n\'est pas disponible pour l\'emprunt.');
            }
        }
    
        return $this->render('emprunt_livre/new.html.twig', [
            'emprunt_livre' => $empruntLivre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_emprunt_livre_show', methods: ['GET'])]
    public function show(Book $book, EmpruntLivreRepository $empruntLivreRepository): Response
    {
        // Récupérer l'emprunt du livre (s'il existe)
        $empruntLivre = $empruntLivreRepository->findOneBy(['book' => $book]);
    
        return $this->render('livre/detail.html.twig', [
            'book' => $book,
            'emprunt_livre' => $empruntLivre, // Passer l'emprunt du livre à la vue
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emprunt_livre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EmpruntLivre $empruntLivre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmpruntLivreType::class, $empruntLivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_emprunt_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emprunt_livre/edit.html.twig', [
            'emprunt_livre' => $empruntLivre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emprunt_livre_delete', methods: ['POST'])]
    public function delete(Request $request, EmpruntLivre $empruntLivre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$empruntLivre->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($empruntLivre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_emprunt_livre_index', [], Response::HTTP_SEE_OTHER);
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
    public function details(Request $request): Response
{
    // Récupérer l'utilisateur connecté
    $user = $this->getUser();

    // Récupérer les emprunts de l'utilisateur
    $loans = $user->IsEmprunts();

    // Gérer la demande d'extension de livre
    if ($request->isMethod('POST')) {
        $loanId = $request->request->get('loan_id'); // ID de l'emprunt
        // Ici, tu peux traiter la demande d'extension, par exemple :
        // - Vérifier si l'utilisateur a le droit d'étendre cet emprunt
        // - Effectuer la logique d'extension de l'emprunt
        // - Rediriger l'utilisateur vers une page de confirmation ou de gestion des emprunts
    }

    return $this->render('emprunt_livre/index.html.twig', [
        'loans' => $loans,
    ]);
}
}