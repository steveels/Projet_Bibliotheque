<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Entity\Commentaires;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommentairesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commentaires::class;
    }

    public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id')->onlyOnIndex(),
        AssociationField::new('book')
            ->formatValue(function ($value, $entity) {
                if ($value !== null) {
                    return $value->getTitle();
                }
                return 'Livre non défini';
            }),
        TextField::new('content'),
        DateTimeField::new('dateAjout'),
        AssociationField::new('comUser')
            ->formatValue(function ($value, $entity) {
                if ($value !== null) {
                    return $value->getFirstName();
                }
                return 'Utilisateur non défini';
            }),
    ];
}

}
