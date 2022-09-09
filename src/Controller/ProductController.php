<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    //affiche les produits d'une categorie
    #[Route('/{slug}', name: 'product_category')]
    public function category($slug, CategoryRepository $categoryRepository)
    {
        // cherche par slug les catégories
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            // envoie un erreur personaliser
            throw $this->createNotFoundException("Catégorie n'existe pas");
        }
        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category,
        ]);
    }

    // affiche les details d'un produit
    #[Route('/product/{slug}', name: 'product_show')]
    public function show($slug, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy(['slug' => $slug]);
        if (!$product) {
            throw $this->createNotFoundException("Produit n'existe pas");
        }
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'slug' => $slug,

        ]);
    }

    //modifier un produit 
    #[Route('/admin/product/{id}/edit', name: 'product_edit')]
    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em)
    {
        $product = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);
        $formView = $form->createView();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em->flush();

            return $this->redirectToRoute('product_show', [
                'slug' => $product->getSlug()
            ]);
        }
        return $this->render(
            'product/edit.html.twig',
            [
                'product' => $product,
                'formView' => $formView
            ]
        );
    }

    //creer un formulaire pour un produit
    #[Route('/admin/product/create', name: 'product_create')]
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {

        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            // creation de slug dans la base comme le nom de produit 
            $product->setSlug(strtolower($slugger->slug($product->getName())));
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', [
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render(('product/create.html.twig'), [
            'formView' => $formView
        ]);
    }
}