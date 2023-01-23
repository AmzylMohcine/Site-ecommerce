<?php

namespace App\EventDispatcher;

use App\Entity\Product;
use Psr\Log\LoggerInterface;
use App\Events\ProductViewEvent;
use PharIo\Manifest\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as MimeEmail;

class ProductViewSubscriber implements EventSubscriberInterface
{

    protected $logger;
    protected $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }


    public static function getSubscribedEvents()
    {
        return [
            'product.view' => "sendMail"
        ];
    }

    public function sendMail(ProductViewEvent $productViewEvent)
    {
        // $email = new TemplatedEmail(); // faire une templeete avec mon email
        // $email->from(new Address("contact@mailcom", "info de la boutique"))
        //     ->to("admi@gmailcom")
        //     ->text("Visiteur entrain de voir la page de produit N" . $productViewEvent->getProduct()->getId())
        //     ->htmlTemplate('email/product_view.html.twig')
        //     ->context([
        //         'product' => $productViewEvent->getProduct()
        //     ])
        //     ->subject("Visite de produit N" . $productViewEvent->getProduct()->getId());

        // $this->mailer->send($email);

        $this->logger->info("l'email a ete bien envoyé pour le produit" . $productViewEvent->getProduct()->getId());
    }
}