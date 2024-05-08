<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Entity\Subscription;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à ce contenu.');
        }

        $plans = $doctrine->getRepository(Plan::class)->findAll();
        $activeSub = $doctrine->getRepository(Subscription::class)->findOneBy(['user' => $user]);
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'plans' => $plans,
            'activeSub' => $activeSub,
        ]);
    }
}
