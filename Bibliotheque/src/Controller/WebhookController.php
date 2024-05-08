<?php

namespace App\Controller;

use Stripe\Webhook;
use App\Entity\Plan;
use App\Entity\User;
use App\Entity\Users;
use App\Entity\Invoice;
use App\Entity\Subscription;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Exception\SignatureVerificationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class WebhookController extends AbstractController
{
    #[Route('/webhook/stripe', name: 'app_webhook_stripe', methods: ['POST'])]
public function handleStripeWebhook(Request $request, LoggerInterface $logger, EntityManagerInterface $entityManager): Response
{
    // Retrieve the Stripe secret key and webhook signature from parameters
    $stripeSecret = $this->getParameter('stripe_sk');
    $webhookSecret = $this->getParameter('stripe_webhook_secret');
    
    $payload = $request->getContent();
    $sigHeader = $request->headers->get('Stripe-Signature');
    
    try {
        $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
    } catch (SignatureVerificationException $e) {
        $logger->error('Échec de la vérification de la signature du webhook : '.$e->getMessage());
        return new Response('Échec de la vérification de la signature du webhook', 400);
    } catch (\UnexpectedValueException $e) {
        $logger->error('Erreur de valeur inattendue : '.$e->getMessage());
        return new Response('Échec de la vérification de la signature du webhook', 400);
    }

    // Handle specific event types
    switch ($event->type) {
        case 'checkout.session.completed':
            $session = $event->data->object;
            $subscriptionId = $session->subscription;

            // Retrieve subscription details from Stripe
            $stripe = new \Stripe\StripeClient($stripeSecret);
            $subscriptionStripe = $stripe->subscriptions->retrieve($subscriptionId);

            // Find user by email
            $customerEmail = $session->customer_details->email;
            $user = $entityManager->getRepository(Users::class)->findOneBy(['email' => $customerEmail]);

            if (!$user) {
                $logger->error('User not found for email: '.$customerEmail);
                return new Response('User not found', 404);
            }

            // Deactivate old active subscription if exists
            $activeSub = $entityManager->getRepository(Subscription::class)->findActiveSubscriptionByUser($user);

            if ($activeSub) {
                $activeSub->setActive(false);
                $entityManager->persist($activeSub);
            }

            // Retrieve plan based on subscription from Stripe
            $planId = $subscriptionStripe->plan->id;
            $logger->info('Plan ID from Stripe: ' . $planId);
            $plan = $entityManager->getRepository(Plan::class)->findOneBy(['stripeId' => $planId]);

            if (!$plan) {
                $logger->error('Plan not found for ID: '.$planId);
                return new Response('Plan not found', 404);
            }

            // Create new subscription
            $subscription = new Subscription();
            $subscription->setUser($user);
            $subscription->setPlan($plan);
            $subscription->setStripeId($subscriptionStripe->id);
            $subscription->setCurrentPeriodStart(new \DateTime('@'.$subscriptionStripe->current_period_start));
            $subscription->setCurrentPeriodEnd(new \DateTime('@'.$subscriptionStripe->current_period_end));
            $subscription->setActive(true);

            $user->setSubscription($subscription);

            $entityManager->persist($subscription);
            $entityManager->persist($user);
            $entityManager->flush();

            break;

        case 'invoice.paid':
            $subscriptionId = $event->data->object->subscription;
            $subscription = $entityManager->getRepository(Subscription::class)->findOneBy(['stripeId' => $subscriptionId]);

            if (!$subscription) {
                $logger->error('Subscription not found for ID: '.$subscriptionId);
                return new Response('Subscription not found', 404);
            }

            // Create a new invoice entity
            $invoice = new Invoice();
            $invoice->setStripeId($event->data->object->id);
            $invoice->setSubscription($subscription);
            $invoice->setNumber($event->data->object->number);
            $invoice->setAmountPaid($event->data->object->amount_paid);
            $invoice->setHostedInvoiceUrl($event->data->object->hosted_invoice_url);
            $invoice->setCreatedAt(new \DateTime());

            $entityManager->persist($invoice);
            $entityManager->flush();

            break;

        default:
            $logger->info('Unhandled webhook event type: '.$event->type);
            return new Response('Unhandled webhook event type', 400);
    }

    return new Response('Webhook handled successfully', 200);
}

}
