<?php

namespace App\Controller\Admin;

use App\Entity\Carousel;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
            ImageField::new('imageName', 'imageFile')
            ->setBasePath('uploads/carousels')
            ->setUploadDir('public/uploads/carousels'),  
            DateTimeField::new('createdAt')->onlyOnIndex()
            
        ];
    }
    
}
