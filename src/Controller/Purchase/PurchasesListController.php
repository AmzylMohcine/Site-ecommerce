<?php


namespace App\Controller\Purchase;


use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PurchasesListController extends AbstractController

{

    #[Route('/purchases', name: 'purchase_index')]
    #[IsGranted('ROLE_USER')] // pour savoir si user est connecté 
    public function index()
    {
        // s'assurer si le user est connecté sinon redérige vers la home page -> security

        /**
         * @var User
         */

        $user = $this->getUser();
        // savoir qui est connecté   -> security 
        // passer utilisateur au twig s'il est connecté -> TWIG  / Response 
        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
    }
}