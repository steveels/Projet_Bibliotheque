<?php

namespace App\Controller;

use App\Entity\Users; // Assure-toi d'importer l'entité User
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home/index.html.twig');
        }

        $lastUsername = $authenticationUtils->getLastUsername();

        $userRepository = $this->entityManager->getRepository(Users::class);
        $user = $userRepository->findOneBy(['email' => $lastUsername]);

        if ($user instanceof Users && $user->isBanni()) {
            $this->addFlash('error', 'Votre compte a été banni. Vous ne pouvez pas vous connecter.');
            return $this->redirectToRoute('app_book'); 
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
