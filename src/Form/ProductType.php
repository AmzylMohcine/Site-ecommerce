<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "Nom de produit",
                "attr" => [
                    "placeholder" => "Tapez le nom de produit "
                ]
            ])
            ->add('shortDescription', TextareaType::class, [
                "label" => "Description courte",
                "attr" => [
                    "placeholder" => "Tapez une desciprtion courte pour le produit pour le visiteur"
                ]
            ])
            ->add('price', MoneyType::class, [
                "label" => "Le prix de produit",
                "attr" => [
                    "placeholder" => "prix.."
                ]
            ])

            ->add('picture', UrlType::class, [
                "label" => "image de produit",
                'attr' => [
                    "placeholder" => "tapez une url d'image "
                ]
            ])

            //entity type faire appel a name comme choice de la base donnees
            ->add('category', EntityType::class, [
                "label" => "Category",
                "placeholder" => "-- Choisir une catégorie --  ",
                "class" => Category::class,
                "choice_label" =>  function (category $category) {;
                    return strtoupper($category->getName());
                }
                // choices c'est les valuer de la liste repmli avec les valeurs de base

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}