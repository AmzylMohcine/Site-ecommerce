<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    /**
     * @var CartService
     */
    protected $cartService;
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    public function __construct(CartService $cartService,  ProductRepository $productRepository)
    {
        $this->cartService = $cartService;
        $this->productRepository = $productRepository;
    }
    #[Route('/cart/add/{id}', name: 'cart_add', requirements: ["id" =>  "\d+"])]
    public function add($id, Request $request): Response
    {

        //securiser est ce que le produit exist

        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("le produit $id n'existe pas ");
        }


        $this->cartService->add($id);


        // /** @var FlashBag */

        // $flashbag = $session->getBag('flashes');

        // $flashbag->add('success', " Le produit est bien ajouté au panier");

        $this->addFlash('success', ["title" => "Félicitation", "content" => "Le produit a bien été ajouté au panier."]);

        // dd($flashbag);

        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('cart_show');
        }

        // dd($session->get('cart', $cart));
        return $this->redirectToRoute('product_show', [
            'slug' => $product->getSlug()
        ]);
    }


    #[Route('/cart', name: 'cart_show')]
    public function show()
    {

        $detailedCart = $this->cartService->getDetailedCartItems();
        $total = $this->cartService->getTotal();

        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total
        ]);
    }


    #[Route('/cart/delete/{id}', name: 'cart_delete', requirements: ["id" =>  "\d+"])]
    public function delete($id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas ");
        }

        $this->cartService->remove($id);

        $this->addFlash("success", ["title" => "supprimé", "content" => "Le produit a bien été supprimé du panier."]);

        return $this->redirectToRoute("cart_show");
    }

    #[Route('/cart/decrement/{id}', name: 'cart_decrement', requirements: ["id" =>  "\d+"])]
    public function decrement($id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {

            throw $this->createNotFoundException(" Produit $id non trouver ");
        }

        $this->cartService->decremente($id);

        $this->addFlash("success", ["title" => "Decre", "content" => "Le produit a bien été décrementé ."]);

        return $this->redirectToRoute("cart_show");
    }
}