<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use App\Stripe\StripeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaimentController extends AbstractController
{
    #[Route('/purchase/pay/{id}', name: 'purchase_paiment_form')]
    #[IsGranted('ROLE_USER', message: "vous devez etre connecter")]
    public function showCarForm($id, PurchaseRepository $purchaseRepository, StripeService $stripeService)
    {
        $purchase = $purchaseRepository->find($id);

        if (!$purchase ||   ($purchase && $purchase->getUser()  !== $this->getUser()) || ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)) {

            return $this->redirectToRoute("cart_show");
        }

        $intent = $stripeService->getPayment($purchase);

        // dd($intent->client_secret);

        return $this->render('purchase/payment.html.twig', [
            'clientSecret' => $intent->client_secret,
            'purchase' => $purchase,
            'publicKey' => $stripeService->getPublicKey()

        ]);
    }
}