<?php

namespace App\EventDispatcher;

use App\Entity\Product;
use Psr\Log\LoggerInterface;
use App\Events\ProductViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewSubscriber implements EventSubscriberInterface
{

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {

        $this->logger = $logger;
    }


    public static function getSubscribedEvents()
    {
        return [
            'product.view' => "ProductView"
        ];
    }

    public function ProductView(ProductViewEvent $productViewEvent)
    {

        $this->logger->info("email envoyé a l'admin pour le produit " . $productViewEvent->getProduct()->getid());
    }
}