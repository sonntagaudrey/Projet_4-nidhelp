<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ServiceCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom de votre prestation'
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Description de votre prestation',
                'attr' => [
                    'rows' => 20, 
                    'placeholder' => 'Décrivez ici les détails de la prestation...'
                ]
            ])
             ->add('category', EntityType::class, [
                'label'         => "Catégorie",
                'class'         => Category::class,  
                'choice_label'  => 'name',              
                'multiple'      => false,                
            ])
            
            ->add('submit', SubmitType::class, [
                'label' => "Enregistrer"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
