<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CategoryTypeCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       $builder
        ->add('name')
        ->add('picture', FileType::class, [
            'label' => 'Image de la catégorie (JPG, PNG, WEBP)',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File(
                    maxSize: '2048k',
                    mimeTypes: [
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                    ],
                    mimeTypesMessage: 'Veuillez uploader une image valide'
                )
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
