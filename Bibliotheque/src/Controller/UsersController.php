<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController
{
    #[Route('/utilisateur/edition/{id}', name: 'users.edit', methods: ['GET', 'POST'])]
    public function edit(Users $user, Request $request, EntityManagerInterface $manager): Response 
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_home'); //redirige vers page d'acceuil si ne correspond pas
        }

        $form = $this->createForm(UsersType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Les informations de votre compte ont bien été modifiées'
            );

            return $this->redirectToRoute('app_book'); // Correction : ajout du retour
        }

        return $this->render('users/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
