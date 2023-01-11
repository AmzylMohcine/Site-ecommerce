<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseConfirmationController extends AbstractController
{
    protected $cartService;
    protected $manager;
    protected $persister;


    public function __construct(CartService $cartService, private RequestStack $requestStack, EntityManagerInterface $manager, PurchasePersister $persister)
    {
        $this->cartService = $cartService;
        $this->manager = $manager;
        $this->persister = $persister;
        $this->requestStack->getCurrentRequest()->getSession();
    }
    #[Route('/purchase/confirm', name: 'purchase_confirm')]
    #[IsGranted('ROLE_USER', message: "vous devez etre connecter")]
    public function confirm(Request $request)
    {
        // lire les données de formulaire 
        // formFactory  / Request 

        $form = $this->createForm(CartConfirmationType::class);

        $form->handleRequest($request);

        // si le form n'est pas soumis // message flach (flashbag)

        if (!$form->isSubmitted()) {
            $this->addFlash('warning', ["title" => "", "content" => "vous devez remplir le formulaire"]);
            return $this->redirectToRoute('cart_show');
        }

        // si je ne suis pas connecté (security)

        $user = $this->getUser();

        // s'il n'ya pas de produit dans mon panier 

        $cartItems = $this->cartService->getDetailedCartItems();

        if (count($cartItems) === 0) {
            $this->addFlash('warning', ["title" => "", "content" => "vous devez remplir le formulaire"]);

            return $this->redirectToRoute('cart_show');
        }

        /** 
         * @var Purchase 
         */

        $purchase = $form->getData();

        $this->persister->storePurchase($purchase);

        // $this->cartService->empty();

        // $this->addFlash('warning', ["title" => "Felicitation", "content" => "la commande a été bien enregistrée"]);

        return $this->redirectToRoute('purchase_paiment_form', [
            'id' => $purchase->getId()
        ]);
    }
}