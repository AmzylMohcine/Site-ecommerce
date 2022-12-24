<?php

namespace App\Controller\Purchase;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseConfirmationController extends AbstractController
{
    #[Route('/purchase/confirm', name: 'purchase_confirm')]
    public function confirm()
    {
    }
}