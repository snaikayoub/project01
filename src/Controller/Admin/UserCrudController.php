<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\GroupRepository;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('matricule'),
            TextField::new('nom'),
            TextField::new('prenom'),
            EmailField::new('email'),
            TextField::new('password')->setFormType(PasswordType::class)->hideOnIndex()->hideWhenUpdating(),
            AssociationField::new('groupe')->setFormTypeOption('query_builder', function (GroupRepository $entityRepository) {
                return $entityRepository->createQueryBuilder('e')
                    //->andWhere('...')
                    ->orderBy('e.nom', 'ASC');
            }),
            ChoiceField::new('roles')
                ->allowMultipleChoices()
                ->autocomplete()
                ->setChoices([
                    "ROLE_USER" => 'ROLE_USER',
                    'ROLE_DELEG' => 'ROLE_DELEG',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                ]),
                ImageField::new('photo')
                    ->setBasePath('uploads/users')->onlyOnIndex(),
                TextField::new('photoFile')
                    ->setFormType(VichImageType::class)->hideOnIndex(),
                ];
    }
    
}
