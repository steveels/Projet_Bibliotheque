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

    #[Route('/utilisateur/edition', name: 'users.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager): Response 
    {
        $user = $this->getUser(); 
    
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
    
        $user = $entityManager->getRepository(Users::class)->find($user->getId());
    
        $form = $this->createForm(UsersType::class, $user);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
    
            if (!$hasher->isPasswordValid($user, $plainPassword)) {
                throw new AccessDeniedException('Mot de passe invalide.');
            }
    
            $entityManager->flush();
    
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
    

    #[Route('/utilisateur/edition-mot-de-passe', name : 'user.edit.password', methods: ['GET', 'POST'])]
    public function editPassword(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager) : Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur actuel
    
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
    
        // Récupérer l'utilisateur à partir de la base de données en utilisant son ID
        $user = $entityManager->getRepository(Users::class)->find($user->getId());
    
        $form = $this->createForm(UserPasswordType::class);
    
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $currentPassword = $form->get('currentPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();
    
            // Valider le mot de passe actuel
            if($hasher->isPasswordValid($user, $currentPassword)){
                $user->setPassword($hasher->hashPassword($user, $newPassword));
    
                $entityManager->flush();
    
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
            'form' => $form->createView(),
        ]);
    }
    
}
