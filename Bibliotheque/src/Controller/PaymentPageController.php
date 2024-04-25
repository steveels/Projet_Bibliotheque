<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentPageController extends AbstractController
{
    // Vérification de l'abonnement de l'utilisateur
public function accessService(Request $request)
{
    // Récupérer l'utilisateur actuellement connecté ou toute autre méthode pour identifier l'utilisateur
    $user = $this->getUser();

    // Récupérer l'abonnement de l'utilisateur depuis la base de données
    $abonnement = $user->getAbonnement();

    // Vérifier si l'abonnement est actif en vérifiant si la date de début est définie
    if ($abonnement && $abonnement->getDateDebut() !== null) {
        // L'utilisateur a un abonnement actif, lui permettre d'accéder au service
        // Votre logique pour permettre à l'utilisateur d'accéder au service ici
    } else {
        // Rediriger l'utilisateur vers la page de paiement Stripe Checkout
        return $this->redirectToRoute('stripe_checkout'); // Remplacez 'stripe_checkout' par le nom de votre route de paiement
    }
}

#[Route('/payment/page', name: 'app_payment_page')]
public function stripeCheckout()
{
    // Générer les informations nécessaires pour le paiement Stripe Checkout
    $stripeSessionId = ...; // Utilisez Stripe API pour créer une session de paiement

    // Afficher la page de paiement Stripe Checkout avec l'ID de session généré
    return $this->render('payment_page/index.html.twig', [
        'stripeSessionId' => $stripeSessionId,
    ]);
}

// Callback pour le succès du paiement Stripe Checkout
public function stripeCheckoutSuccess(Request $request)
{
    // Récupérer l'utilisateur actuellement connecté
    $user = $this->getUser();

    // Mettre à jour les informations de l'abonnement de l'utilisateur dans la base de données
    $user->getAbonnement()->setDateDebut(new \DateTime()); // Par exemple, définir la date de début sur la date actuelle
    // Mettez à jour d'autres informations d'abonnement si nécessaire

    // Enregistrer les modifications dans la base de données
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->flush();

    // Rediriger l'utilisateur vers la page de confirmation ou de remerciement
    return $this->redirectToRoute('confirmation_page'); // Remplacez 'confirmation_page' par le nom de votre route de confirmation
}


}
