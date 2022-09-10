<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\Type\PriceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

            //pricetype c'est un type de prix creer 
            ->add('price', PriceType::class, [
                "label" => "Le prix de produit",
                "attr" => [
                    "placeholder" => "prix.."
                ],
                // divise par 100 apres le submit , personnalider sinon on peut utiliser divisor de type MoneyType
                'divide' => 100


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


        // apres l'ajour de prix , le transformer en centimes dans la base 

        // $builder->get('price')->addModelTransformer(new CentimeTransformer);


        // $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

        //     $product = $event->getData();

        //     if ($product->getPrice() !== null) {
        //         $product->setPrice($product->getPrice() * 100);
        //     }
        // });

        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

        //     $form = $event->getForm();

        //     /** @var Product */
        //     $product = $event->getData();

        //     if ($product->getPrice() !== null) {
        //         $product->setPrice($product->getPrice() / 100);
        //     }


        //     if ($product->getId() === null) {


        //         $form->add('category', EntityType::class, [
        //             "label" => "Category",
        //             "placeholder" => "-- Choisir une catégorie --  ",
        //             "class" => Category::class,
        //             "choice_label" =>  function (category $category) {;
        //                 return strtoupper($category->getName());
        //             }

        //         ]);
        //     }
        // });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}