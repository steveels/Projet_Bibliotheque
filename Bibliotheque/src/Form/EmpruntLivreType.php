<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Users;
use App\Entity\EmpruntLivre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EmpruntLivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
    
       
        $builder
        ->add('dateEmprunt', DateType::class, [
            'data' => new \DateTime(), // Définit la date du jour comme valeur par défaut
        ])
        ->add('dateRestitution', DateType::class, [
            'data' => new \DateTime( '+6 day'), // Définit la date dans 6 jours comme valeur par défaut
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
