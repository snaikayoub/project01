<?php

namespace App\Controller\Admin;

use App\Entity\Carousel;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CarouselCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carousel::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            TextField::new('description'),
            BooleanField::new('isPublished'),
            ImageField::new('imageName')
            ->setBasePath('uploads/carousels')->onlyOnIndex(),
            TextField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex()
            
        ];
    }
    
}
