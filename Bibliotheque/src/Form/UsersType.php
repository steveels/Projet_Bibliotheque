<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Prenom'
        ])
        ->add('lastname', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Nom'
        ])
        ->add('birthdate', BirthdayType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Date d\'anniversaire'
        ])
        ->add('adress', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Adresse'
        ])
        ->add('city', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Ville'
        ])
        ->add('zip_code', TextType::class, [
            'attr'=>[
                'class'=> 'form-control'
            ],
            'label' => 'Code postale'
        ])
        ->add('phoneNumber', TextType::class, [
            'attr'=>[
                'class'=> 'form-control'
            ],
            'label' => 'Numéro de téléphone'
        ])
        ->add('submit', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary mt-4'
            ]
        ])
        ->add('plainPassword', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'attr' => [
                'autocomplete' => 'new-password',
                'class' => 'form-control'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
            'label' => 'Mot de passe'
        ]);
        // ->add('email')
        // ->add('roles')
        // ->add('password')
        // ->add('birthdate', null, [
        //     'widget' => 'single_text',
        // ])
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
