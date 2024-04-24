<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BanController extends AbstractController
{
    #[Route('/ban', name: 'app_ban')]
    public function index(UsersRepository $userRepo): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }
        
        $bannedUsers = $userRepo->findBy(['banni' => true]);

        return $this->render('ban/index.html.twig', [
            'controller_name' => 'BanController',
            'bannedUsers' => $bannedUsers,
        ]);
    }

    #[Route('/unban/{id}', name: 'app_unban', methods: ['GET'])]
    public function unbanUser(Users $user, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }

        $user->setBanni(false);
        $entityManager->flush();

        return $this->redirectToRoute('app_ban');
    }
}
