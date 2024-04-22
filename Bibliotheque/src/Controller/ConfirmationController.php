<?php

namespace App\Controller;

use App\Entity\EmpruntLivre;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfirmationController extends AbstractController
{
    #[Route('/confirmation', name: 'app_confirmation')]
    public function index(EmpruntLivre $emprunt): Response
    {
        return $this->render('confirmation/index.html.twig', [
            'emprunt' => $emprunt,
        ]);
    }



}



