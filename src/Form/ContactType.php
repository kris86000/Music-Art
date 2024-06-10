<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'attr' => ['class' => 'form-control mb-2', 'placeholder' => 'Nom']
        ])
        ->add('prenom', TextType::class, [
            'attr' => ['class' => 'form-control mb-2', 'placeholder' => 'Prénom']
        ])
        ->add('email', EmailType::class, [
            'attr' => ['class' => 'form-control mb-2', 'placeholder' => 'Email']
        ])
        ->add('demande', ChoiceType::class, [
            'choices' => [
            'Remboursement' => 'remboursement',
            'Problème technique' => 'probleme technique',
            'Renseignements' => 'renseignements',
            'Autre' => 'Autre',
            ],
            'attr' => ['class' => 'form-control mb-2'],
            ])
        ->add('message', TextareaType::class, [
            'attr' => ['class' => 'form-control mb-2', 'placeholder' => 'Message']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
