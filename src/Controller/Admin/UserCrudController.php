<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Service\CsvService;
use App\Repository\GroupRepository;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    private CsvService $csvService;

    public function __construct(CsvService $csvService)
    {
        $this->csvService = $csvService;
    }
    
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $export = Action::new('export', 'Exporter')
            ->setIcon('fa fa-download')
            ->linkToCrudAction('export')
            ->setCssClass('btn btn-success')
            ->createAsGlobalAction();
        
        /*$import = Action::new('import','Upload_CSV')
            ->setIcon('fa fa-upload')
            ->linkToCrudAction('import')
            ->setCssClass('btn btn-success')
            ->createAsGlobalAction();*/

        return $actions
            ->add(Crud::PAGE_INDEX, $export)
            //->add(Crud::PAGE_INDEX, $import)
    ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('nom')
            ->add('prenom')
            ->add('matricule')
            ->add('groupe')
            ->add('roles')
            ->add('updatedAt')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('matricule'),
            TextField::new('nom'),
            TextField::new('prenom'),
            EmailField::new('email'),
            TextField::new('password')->setFormType(PasswordType::class)->onlyWhenCreating(),
            AssociationField::new('groupe')->setFormTypeOption('query_builder', function (GroupRepository $entityRepository) {
                return $entityRepository->createQueryBuilder('e')
                    //->andWhere('...')
                    ->orderBy('e.nom', 'ASC');
            }),

            AssociationField::new('groupesGeres')->setFormTypeOption('query_builder', function (GroupRepository $entityRepository) {
                return $entityRepository->createQueryBuilder('e')
                    //->andWhere('...')
                    ->orderBy('e.nom', 'ASC');
            }),

            DateTimeField::new('updatedAt')->onlyOnIndex(),
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

    public function configureCrud(Crud $crud): Crud
        {
            return $crud
                // the names of the Doctrine entity properties where the search is made on
                // (by default it looks for in all properties)
                ->setSearchFields(['nom', 'prenom', 'email'])
                // use dots (e.g. 'seller.email') to search in Doctrine associations
                ////->setSearchFields(['name', 'description', 'seller.email', 'seller.address.zipCode'])
                // set it to null to disable and hide the search box
                ////->setSearchFields(null)
                // call this method to focus the search input automatically when loading the 'index' page
                ////->setAutofocusSearch()

                // defines the initial sorting applied to the list of entities
                // (user can later change this sorting by clicking on the table columns)
                ////->setDefaultSort(['id' => 'DESC'])
                ////->setDefaultSort(['id' => 'DESC', 'title' => 'ASC', 'startsAt' => 'DESC'])
                // you can sort by Doctrine associations up to two levels
                ////->setDefaultSort(['seller.name' => 'ASC'])

                // the max number of entities to display per page
                ->setPaginatorPageSize(30)
                // the number of pages to display on each side of the current page
                // e.g. if num pages = 35, current page = 7 and you set ->setPaginatorRangeSize(4)
                // the paginator displays: [Previous]  1 ... 3  4  5  6  [7]  8  9  10  11 ... 35  [Next]
                // set this number to 0 to display a simple "< Previous | Next >" pager
                ->setPaginatorRangeSize(4)

                // these are advanced options related to Doctrine Pagination
                // (see https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/tutorials/pagination.html)
                ->setPaginatorUseOutputWalkers(true)
                ->setPaginatorFetchJoinCollection(true)
            ;
        }
        public function export(Request $request)
        {
            $context = $request->attributes->get(EA::CONTEXT_REQUEST_ATTRIBUTE);
            $fields = FieldCollection::new($this->configureFields(Crud::PAGE_INDEX));
            $filters = $this->container->get(FilterFactory::class)->create($context->getCrud()->getFiltersConfig(), $fields, $context->getEntity());
            $users = $this->createIndexQueryBuilder($context->getSearch(), $context->getEntity(), $fields, $filters)
                ->getQuery()
                ->getResult();
            $data = [];
            /**
             * @var $record User
             */
            foreach ($users as $record) {
                $roles ="";
                $groupes="";
                foreach($record->getRoles() as $role)
                    {
                        if(!$roles){
                            $roles = $role;
                        }else{
                            $roles = $roles . '-' . $role;
                        }
                        
                    };

                foreach($record->getGroupesGeres() as $groupe)
                    {
                        if(!$groupes){
                            $groupes = $groupe;
                        }else{
                            $groupes = $groupes . '-' . $groupe;
                        }
                        
                    };

                $data[] = [
                    'matricule' => $record->getMatricule(),
                    'email' => $record->getEmail(),
                    'Nom' => $record->getNom(),
                    'prenom' => $record->getPrenom(),
                    'Roles' => $roles,
                    'groupe' => $record->getGroupe(),
                    'groupesGeres'=>$groupes,
                    'updatedAt'=>$record->getUpdatedAt()->format('d-m-Y H:i:s')
                ];
            }

            $filename = 'export_users_'.date_create()->format('d-m-y').'.csv';

            return $this->csvService->export($data,$filename);

        
        }
    
}
