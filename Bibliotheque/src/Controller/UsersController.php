<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/utilisateur/edition/{id}', name: 'users.edit', methods: ['GET', 'POST'])]
    public function edit(Users $user, Request $request, UserPasswordHasherInterface $hasher): Response 
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_home'); 
        }

        $form = $this->createForm(UsersType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $formData = $form->getData();
            $plainPassword = $form->get('plainPassword')->getData();

            if (!$hasher->isPasswordValid($user, $plainPassword)) {
                throw new AccessDeniedException('Mot de passe invalide.');
            }

            $manager = $this->entityManager; 
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Les informations de votre compte ont bien été modifiées'
            );

            return $this->redirectToRoute('app_book');
        }

        return $this->render('users/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateur/edition-mot-de-passe/{id}', name : 'user.edit.password', methods: ['GET', 'POST'])]
    public function editPassword(Users $user, Request $request, UserPasswordHasherInterface $hasher) : Response
    {
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $currentPassword = $form->get('currentPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();

            if($hasher->isPasswordValid($user, $currentPassword)){
                $user->setPassword($hasher->hashPassword($user, $newPassword));

                $manager = $this->entityManager; 
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le mot de passe a bien été modifié.'
                );

                return $this->redirectToRoute('app_book');
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect.'
                );
            }
        }

        return $this->render('users/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

