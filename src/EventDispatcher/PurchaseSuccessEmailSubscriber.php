<?php
namespace App\EventDispatcher;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Address;
use App\Events\PurchaseSuccessEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface 
{
    protected $logger;
    protected $mailer;
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
             // C'est à dire je dis à  ma function à tout momment,  
             //quant tu reçois cet évenment là appelle ma function sendSuccessEmail( 'purchase.success' => 'sendSuccessEmail')
             'purchase.success' => "sendSuccessEmail"
         ];  
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent) 
    {
    // 1. Récupérer l'utilisateur actuellement en ligne(pour conaitre son adresse)
     // Security

     /** 
      * @var User
       */
      $currentUser =  $this->security->getUser();

     // 2. Récupérer la commande(je la trouverai dans PurchaseSuccessEvent)
     $purchase = $purchaseSuccessEvent->getPurchase();


     // 3 . Ecrire le mail(nouveau TemplatedEmail)
     $email = new TemplatedEmail();
     $email->to(new Address($currentUser->getEmail(), $currentUser->getFullName()))
          ->from("contact@mail.com")
          ->subject("Bravo, votre commande ({$purchase->getId()}) a bien été confirmée")
          ->htmlTemplate('emails/purchase_success.html.twig')
          ->context([
              'purchase' => $purchase,
              'user' =>  $currentUser
          ]);

     // 5. Envoyer l'email
     $this->mailer->send($email);
     $this->logger->info("Email envoyé à l'admin pour le produit " . $purchaseSuccessEvent->getPurchase()->getId());
 
     // Service mailerInterface
    

    }
}