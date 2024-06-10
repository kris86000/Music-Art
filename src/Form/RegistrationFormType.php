<?php
// Formulaire de création de compte //
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomUser', TextType::class, [
                'attr' => ['class' => 'form-control mb-2', 'placeholder' => 'Nom']
            ])
            ->add('prenomUser', TextType::class, [
                'attr' => ['class' => 'form-control mb-2', 'placeholder' => 'Prénom']
            ])
            ->add('pseudo', TextType::class, [
                'attr' => ['class' => 'form-control mb-2', 'placeholder' => 'Pseudo']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control mb-2', 'placeholder' => 'Email']
            ])
            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'form-control mb-2', 'placeholder' => 'Adresse']
            ])
            ->add('codePostal', TextType::class, [
                'attr' => ['class' => 'form-control mb-2', 'placeholder' => 'Code Postal']
            ])
            ->add('ville', TextType::class, [
                'attr' => ['class' => 'form-control mb-2', 'placeholder' => 'Ville']
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'attr' => ['class' => 'mx-2'],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Accepter les conditions d'utilisation.",
                    ]),
                ],
                'label' => "Accepter Condition d'utilisation: "
            ])

            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['class' => 'form-control mb-3', 'autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'choisir Mot de Passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre Mot de passe doit être composé de  {{ limit }} caractères minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
