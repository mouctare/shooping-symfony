<?php

namespace App\EventDispatcher;
use App\Event\PurchaseSuccessEvent;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements  EventSubscriberInterface
{

    protected $mailer;
    protected $security;

    public function __construct(MailerInterface $mailer, Security $security)
    {
        $this->mailer = $mailer;
        $this->security = $security;
    }
    public static function getSubscribedEvents()
    {
        return [
          'purchase.success' => 'sendSuccessEmail'
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
    { 
        // 1.Récuperer le user actuellement connecté(pour connaitre son adresse)=>Security
     /**
      * @var User
      */
     $currentUser = $this->security->getUser();


        // 2.Récuperer la commande (Je la trouverai dans PurchaseSuccessEvent)

        $purchase = $purchaseSuccessEvent->getPurchase();

        // 3. Ecrire le mail (nouveau TempletEmail)

        $email = new TemplatedEmail();
        $email->to(new Address($currentUser->getEmail(), $currentUser->getFullName()))
             ->from("contact@yahoo.fr")
             ->subject("Bravo, votre commande ({$purchase->getId()}) a bien été confirméé")
             ->htmlTemplate('emails/purchase_success.html.twig')
             ->context([
                 'purchase' => $purchase,
                 'user' => $currentUser
             ]);

        // 5  Envoyer le mail(MailerInterface)
        $this->mailer->send($email);

    }
}