<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartService
{
    private $requestStack;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->requestStack = $requestStack;
        $this->ProductRepository = $productRepository;
    }

    public function add(int $id)
    {

        //creer un tableau de carte 
        // creer un tableau vide s'il n'existe aucun produit dans la carte pour la nouvelle session 
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        //voi si le produit id exsite deja dans la carte 

        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        //enregistrer le tableau dans la session 
        $session->set('cart', $cart);
    }


    public function remove(int $id)
    {
        $session = $this->requestStack->getSession();

        $cart = $session->get('cart', []);

        unset($cart[$id]);

        $session->set('cart', $cart);
    }

    public function decremente(int $id)
    {
        $session = $this->requestStack->getSession();

        $cart = $session->get('cart', []);

        if (!array_key_exists($id, $cart)) {
            return;
        }

        // si le panier contient  produit 

        if ($cart[$id] === 1) {

            $this->remove($id);
            return;
        }
        // si le panier contient plus qu'1 produit 
        $cart[$id]--;
        $session->set('cart', $cart);
    }


    public function getTotal(): int
    {
        $total = 0;
        $session = $this->requestStack->getSession();

        foreach ($session->get('cart', []) as $id => $qty) {

            $product = $this->ProductRepository->find($id);

            if (!$product) {
                continue;
            }

            $total += $product->getPrice() * $qty;
        }

        return $total;
    }


    public function getDetailedCartItems(): array
    {
        $session = $this->requestStack->getSession();

        $detailedCart = [];
        $total = 0;

        foreach ($session->get('cart', []) as $id => $qty) {

            $product = $this->ProductRepository->find($id);

            if (!$product) {
                continue;
            }

            $detailedCart[] = new CartItem($product, $qty);
        }

        return $detailedCart;
    }
}