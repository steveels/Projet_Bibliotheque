<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Repository\UsersRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UsersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs')
            ->setEntityLabelInSingular('Utilisateur')

            ->setPageTitle("index", "SymRecipe - Administration des utilisateurs");
    }

    public function configureFields(string $pageName): iterable
    {
        return [ 
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('email')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('lastname'),
            TextField::new('firstname'),
            DateTimeField::new('birthdate'),
            ArrayField::new('roles'),
            TextField::new('adress'),
            TextField::new('city'),
            TextField::new('zip_code'),
            IntegerField::new('phoneNumber'),
            ArrayField::new('reservations'),
            ArrayField::new('empruntLivres'),
            ArrayField::new('commentaires'),
            BooleanField::new('banni')
            
        ];
    }
}