<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CartConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName',  TextType::class, [
                'label' => 'Nom Complet',
                'attr' => [
                    'placeholder' => ' Nom Complet pour la livraison'
                ]
            ])
            ->add('adress', TextareaType::class, [
                'label' => 'Adresse Complete',
                'attr' => [
                    'placeholder' => ' Adresse complete pour la livraison '
                ]
            ])
            ->add('codePostale', TextType::class, [
                'label' => 'Code Postale',

                'attr' => [
                    'placeholder' => " Code postale pour la livraison "
                ]
            ])

            ->add('city', TextType::class, [
                'label' => 'Ville',

                'attr' => [
                    'placeholder' => " ville pour la livraison "
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}