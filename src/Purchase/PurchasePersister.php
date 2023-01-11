<?php

namespace App\Purchase;

use App\Cart\CartService;
use DateTime;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchasePersister
{


    protected $security;
    protected $cartService;
    protected $manager;

    public function __construct(Security $security, CartService $cartService, EntityManagerInterface $manager)
    {
        $this->security = $security;
        $this->cartService = $cartService;
        $this->manager = $manager;
    }

    public function storePurchase(Purchase $purchase)
    {

        // nous allons creer une purchase  et la lier avec l'utilisateur connecté 

        $purchase->setUser($this->security->getUser())
            ->setPurchasedAt(new DateTime())
            ->setTotal($this->cartService->getTotal());


        $this->manager->persist($purchase);

        // lier avec les produits dans panier 

        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {


            $purchaseItem = new PurchaseItem();

            $purchaseItem->setProduct($cartItem->product)
                ->setPurchase($purchase)
                ->setProductName($cartItem->product->getName())
                ->setProductPrice($cartItem->product->getPrice())
                ->setQuantity($cartItem->qty)
                ->setTotal($cartItem->getTotal());



            $this->manager->persist($purchaseItem);
        }

        // enregistrer la commande (Donctrine)

        $this->manager->flush();
    }
}