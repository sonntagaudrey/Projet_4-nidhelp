<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('name', TextType::class, [
                'label' => 'Nom de famille',
                'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire'),
                ]
            ])

            ->add('firstname', TextType::class, [
                'label' => 'Prénom', 
                'constraints' => [
                    new NotBlank(message: 'Le prénom est obligatoire'),
                ]
            ])         

            ->add('address', TextType::class, [
                'label' => 'Adresse', 
                'constraints' => [
                    new NotBlank(message: 'L\'adresse est obligatoire'),
                ]
            ])
                ->add('postcode', TextType::class, [
                'label' => 'Code postal', 
                'constraints' => [
                    new NotBlank(message: 'Le code postal est obligatoire'),
                ]
            ])

            ->add('town', TextType::class, [
                'label' => 'Ville', 
                'constraints' => [
                    new NotBlank(message: 'La Ville est obligatoire'),
                ]
            ])

            ->add('phone', TextType::class, [
                'label' => 'Téléphone', 
                'constraints' => [
                    new NotBlank(message: 'Le téléphone est obligatoire'),
                ]
            ])
            
            ->add('email',EmailType::class, [
                'label' => 'Adresse e-mail', 
                'constraints' => [
                    new NotBlank(message: 'L\email est obligatoire'),
                ]
            ])

            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message'   => 'Les champs doivent être identiques', 
                'required'          => true,

                'first_options'     => ['label' => 'Mot de passe'],
                'second_options'    => ['label' => 'Confirmer le mot de passe'],

                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez saisir un mot de passe',
                    ),
                    new Length(
                        min: 12,
                        minMessage: 'Pour votre sécurité, votre mot de passe doit comporter un minimum de {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        max: 4096,
                    ),
                    new PasswordStrength(),
                    new NotCompromisedPassword(),
                ],
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label'  => 'J\'accepte les conditions d\'utilisation',
                'constraints' => [
                    new IsTrue(
                        message: 'Veuillez accepter nos conditions générales d\'utilisation.',
                    ),
                ],
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
