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
            'label' => 'First name'
        ])
        ->add('lastname', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Name'
        ])
        ->add('birthdate', BirthdayType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Birthday date'
        ])
        ->add('adress', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Adress'
        ])
        ->add('city', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'City'
        ])
        ->add('zip_code', TextType::class, [
            'attr'=>[
                'class'=> 'form-control'
            ],
            'label' => 'Zip code'
        ])
        ->add('phoneNumber', TextType::class, [
            'attr'=>[
                'class'=> 'form-control'
            ],
            'label' => 'Phone number'
        ])
        ->add('plainPassword', PasswordType::class, [
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
                    'max' => 4096,
                ]),
            ],
            'label' => 'Validez votre mot de passe pour modifier vos informations'
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Submit',
            'attr' => [
                'class' => 'btn btn-primary mt-4' 
            ]
        ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
