<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Events\PurchaseSuccessEvent;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PurchasePaymentSuccessController extends AbstractController
{

    #[Route('/purchase/terminate/{id}', name: 'purchase_payment_success')]
    #[IsGranted('ROLE_USER', message: "vous devez etre connecter")]
    public function success($id, PurchaseRepository $purchaseRepository, EntityManagerInterface $manager, CartService $cartService, EventDispatcherInterface $dispatcher)
    {
        // 1  - je recupere la commande 
        $purchase = $purchaseRepository->find($id);

        if (!$purchase ||   ($purchase && $purchase->getUser()  !== $this->getUser()) || ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)) {

            $this->addFlash('warning', ["title" => "Non", "content" => "la commande n'existe pas "]);

            return $this->redirectToRoute("purchase_index");
        }

        $purchase->setStatus(Purchase::STATUS_PAID);

        // 2 - la passr ene status paid 

        $manager->flush();

        // 3 - vider le panier 

        $cartService->empty();

        // lancer un event qui permette aux autres dev de reagir 

        $purchaseEvent = new PurchaseSuccessEvent($purchase);

        $dispatcher->dispatch($purchaseEvent, 'purchase.success');



        // 4- rediriger vers la listes des commandes 

        $this->addFlash('success', ["title" => "BRAVO", "content" => "la commande a été payée et confirmée "]);

        return $this->redirectToRoute("purchase_index");
    }
}