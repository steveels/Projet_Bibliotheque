<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\EmpruntLivre;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntLivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateEmprunt', null, [
                
            ])
            ->add('dateRestitution', null, [
                
            ])
            ->add('dateRestitutionEffective', null, [
                
            ])
            ->add('book', EntityType::class, [
                'class' => Book::class,
                'choice_label' => 'id',
            ])
            ->add('user', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmpruntLivre::class,
        ]);
    }
}
