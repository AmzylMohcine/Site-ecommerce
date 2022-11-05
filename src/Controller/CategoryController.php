<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security as CoreSecurity;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{

    // protected $categoryRepository;

    // public function __construct(CategoryRepository $categoryRepository)
    // {
    //     $this->categoryRepository = $categoryRepository;
    // }

    // public function renderMenuList()
    // {
    //     $categories = $this->categoryRepository->findAll();

    //     return $this->render("category/_menu.html.twig", [
    //         'categories' => $categories
    //     ]);
    // }


    #[Route('/admin/category/create', name: 'category_create')]
    public function create(EntityManagerInterface $em, Request $request, SluggerInterface $slugger)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();

        return $this->render(
            'category/create.html.twig',
            [
                'formView' => $formView
            ]
        );
    }


    #[Route('/admin/category/{id}/edit', name: 'category_edit')]
    public function edit($id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, CoreSecurity $security)
    {

        $category = $categoryRepository->find($id);

        if (!$category) {
            return new NotFoundHttpException("Categorie n'existe pas ");
        }

        // $this->denyAccessUnlessGranted('CAN_EDIT', $category, "Non vous n'aves pas le droit");


        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();

        return $this->render(
            'category/edit.html.twig',
            [
                'category' => $category,
                'formView' => $formView
            ]
        );
    }
}