<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom'
            ])

            ->add('firstname', TextType::class,[
                'label' => 'Prénom'
            ])

            ->add('adress', TextType::class,[
                'label' => 'Adresse'
            ])

            ->add('postcode', TextType::class,[
                'label' => 'Code postal'
            ])

            ->add('town', TextType::class,[
                'label' => 'Ville'
            ])

            ->add('email', TextType::class,[
                'label' => 'Email'
            ])

            ->add('pwd', TextType::class,[
                'label' => 'Mot de passe'
            ])
            
            ->add('phone', TextType::class,[
                'label' => 'Téléphone'
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
