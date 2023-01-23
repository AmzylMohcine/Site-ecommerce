<?php

namespace App\EventDispatcher;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use App\Events\PurchaseSuccessEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Address;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
    protected $mailer;
    protected $logger;
    protected $security;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->security = $security;
    }
    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendSuccessEmail'
        ];
    }
    public  function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {

        // recuperer user en ligne => security 

        /**
         * @var User
         */
        $curret_User = $this->security->getUser();

        // recuprer commande => purchasesuccessEvent

        $purchase = $purchaseSuccessEvent->getPurchase();

        //ecrire email => templated Email

        $email = new TemplatedEmail();
        $email->to(new Address($curret_User->getEmail(), $curret_User->getFullName()))
            ->from("Support@boutique.com")
            ->subject("Votre Commande ({$purchase->getId()}) a bien été confirmer ")
            ->htmlTemplate('emails/purchase_success.html.twig')
            ->context([
                'purchase' => $purchase,
                'user' => $curret_User
            ]);
        //envoyer email

        $this->mailer->send($email);

        $this->logger->info("email envoyé pour la commande" . $purchaseSuccessEvent->getPurchase()->getId());
    }
}