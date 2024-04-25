<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentPageController extends AbstractController
{
    #[Route('/payment/page', name: 'app_payment_page')]
    public function index(): Response
    {
        return $this->render('payment_page/index.html.twig', [
            'controller_name' => 'PaymentPageController',
        ]);
    }
}
