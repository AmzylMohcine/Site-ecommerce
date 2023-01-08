<?php

namespace App\Controller\Purchase;

use DateTime;
use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PurchaseConfirmationController extends AbstractController
{
    protected $cartService;
    protected $manager;


    public function __construct(CartService $cartService, private RequestStack $requestStack, EntityManagerInterface $manager)
    {
        $this->cartService = $cartService;
        $this->manager = $manager;
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

        // nous allons creer une purchase  et la lier avec l'utilisateur connecté 

        $purchase->setUser($user)
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


        $this->cartService->empty();

        $this->addFlash('warning', ["title" => "Felicitation", "content" => "la commande a été bien enregistrée"]);

        return $this->redirectToRoute('purchase_index');
    }
}