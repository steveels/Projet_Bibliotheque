<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Form\UserPasswordType;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

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
            return $this->redirectToRoute('app_home'); //redirige vers la page d'accueil si ne correspond pas
        }

        $form = $this->createForm(UsersType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $manager = $this->entityManager; // Récupération de l'EntityManager
            $manager->persist($formData);
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
            $formData = $form->getData();
            $plainPassword = $formData['plainPassword'];
            $newPassword = $formData['newPassword'];

            if($hasher->isPasswordValid($user, $plainPassword)){
                $user->setPlainPassword($newPassword);

                $manager = $this->entityManager; // Récupération de l'EntityManager
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

// namespace App\Controller;

// use App\Entity\Users;
// use App\Form\UsersType;
// use App\Form\UserPasswordType;
// use Symfony\Component\HttpFoundation\Request; 
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
// use Doctrine\ORM\EntityManagerInterface; 

// class UsersController extends AbstractController
// {
//     private $entityManager; 

//     public function __construct(EntityManagerInterface $entityManager) 
//     {
//         $this->entityManager = $entityManager; 
//     }

//     #[Route('/utilisateur/edition', name: 'users.edit', methods: ['GET', 'POST'])]
//     public function edit(Request $request): Response 
//     {
//         $user = $this->getUser();

//         if (!$user) {
//             return $this->redirectToRoute('app_login');
//         }

//         $form = $this->createForm(UsersType::class, $user);

//         $form->handleRequest($request);
//         if ($form->isSubmitted() && $form->isValid()) {
//             $this->entityManager->flush(); 

//             $this->addFlash(
//                 'success',
//                 'Les informations de votre compte ont bien été modifiées'
//             );

//             return $this->redirectToRoute('app_book');
//         }

//         return $this->render('users/edit.html.twig', [
//             'form' => $form->createView(),
//         ]);
//     }

//     #[Route('/utilisateur/edition-mot-de-passe', name : 'user.edit.password', methods: ['GET', 'POST'])]
//     public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder) : Response
//     {
//         $user = $this->getUser();

//         $form = $this->createForm(UserPasswordType::class);

//         $form->handleRequest($request);
//         if($form->isSubmitted() && $form->isValid()){
//             $formData = $form->getData();
//             $plainPassword = $formData['plainPassword'];
//             $newPassword = $formData['newPassword'];

//             if($passwordEncoder->isPasswordValid($user, $plainPassword)){
//                 $hashedPassword = $passwordEncoder->encodePassword($user, $newPassword);
//                 $user->setPassword($hashedPassword);

//                 $this->entityManager->persist($user); 
//                 $this->entityManager->flush(); 

//                 $this->addFlash(
//                     'success',
//                     'Le mot de passe a bien été modifié.'
//                 );

//                 return $this->redirectToRoute('app_book');
//             } else {
//                 $this->addFlash(
//                     'warning',
//                     'Le mot de passe renseigné est incorrect.'
//                 );
//             }
//         }

//         return $this->render('users/edit_password.html.twig', [
//             'form' => $form->createView()
//         ]);
//     }
// }
