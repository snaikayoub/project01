<?php

namespace App\Form;

use App\Entity\Astreinte;
use App\Service\ManagedUsersByLogged;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AstreinteType extends AbstractType
{
    private $users ;

    public function __construct(Security $security, ManagedUsersByLogged $ManagedUsersByLogged )
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
        $this->users= $ManagedUsersByLogged->getUsers($this->security->getUser()->getUserIdentifier());        
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {          
        $builder
            ->add('matricule',ChoiceType::class,[
                'choices' => $this->users,
                'attr' => ['class' => 'form-control'],
                
            'choice_label' => 'matricule',
            ])

            ->add('relatedMonth',TextType::class,[
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateDebut',DateType::class,[
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'attr' => ['class' => 'form-control','type' => 'date'],
            ])
            ->add('dateFin',DateType::class,[
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'attr' => ['class' => 'form-control','type' => 'date'],
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Astreinte::class,
        ]);
    }
}
