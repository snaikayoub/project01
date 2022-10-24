<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class GroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Group::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('nom'),
            TextEditorField::new('designation'),
            AssociationField::new('gestionnaires')->setFormTypeOption('query_builder', function (UserRepository $entityRepository) {
                return $entityRepository->createQueryBuilder('e')
                    //->andWhere('...')
                    ->orderBy('e.nom', 'ASC');
            }),
        ];
    }
   
}
