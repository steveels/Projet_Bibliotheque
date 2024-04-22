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
        TextField::new('content'),
        DateTimeField::new('dateAjout')->onlyOnIndex(),
        AssociationField::new('comUser')
            ->formatValue(function ($value, $entity) {
                // Vérifiez d'abord si $value est défini et non null
                if ($value !== null) {
                    return $value->getFirstName();
                }
                // Si $value est null, retournez une valeur par défaut ou un message d'erreur
                return 'Utilisateur non défini';
            }),
    ];
}

}
