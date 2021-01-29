<?php
namespace App\Controller\Purchase;

use DateTime;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class PurchaseConfirmationController extends AbstractController
{
    protected  $formFactory;
    protected  $router;
    protected  $security;
    protected  $cartService;
    protected  $em;


    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, Security $security, CartService $cartService,  EntityManagerInterface $em)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->security = $security;
        $this->cartService = $cartService;
        $this->em = $em;

    }
    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     */
  public function confirm(Request $request, FlashBagInterface $flashBag)
  {
      // Ici on met la request dans le méthode car la request est unique à chaque appelle
     // 1. Nous voulons lire les données du formulaire
     // FormFactoryInterface / Request
     $form = $this->formFactory->create(CartConfirmationType::class);
     
     $form->handleRequest($request);
     // 2. Si le formulaire n'a pas été soummis: dégager
     if(!$form->isSubmitted()) {
         // Message Flash redirection
         $flashBag->add('warning', 'Vous devez remplir le formulaire de confirmation');
         return new RedirectResponse($this->router->generate('cart_show'));
     }

     // 3. si je ne suis pas connecté : dégager (Security)

     $user = $this->security->getUser();

     if(!$user){
        throw new AccessDeniedException("Vous devez étre connecté pour confirmez une commandes");
     }


     // 4. si il n'y a pas de de produits dans le panier : dégager (CartService)
     $cartItems = $this->cartService->getDetailedCartItems();

     if(count($cartItems) === 0){
        $flashBag->add('warning', 'Vous ne pouvez pas confirmez une comande avec un panier vide');
        return new RedirectResponse($this->router->generate('cart_show'));
    }
     // 5. Nous allons créer une purchase 
    /**
     * @var Purchase
     */
      $purchase = $form->getData(); // Ici on récupère les ,donnée du formulaire
    
   // 6. Nous allons la lier ave cle user actuellement connecté (Security)
       $purchase->setUser($user)
                ->setPurchasedAt(new DateTime());
                $this->em->persist($purchase);

   // 7. Nous allons la lier avec les produits qui sont dan le panier (CartService)
   $total = 0;

   foreach($this->cartService->getDetailedCartItems() as $cartItem){
       // Cette bouble permet de récupérer un achat du tableau de la function getDetailedCartItems du service cartService
       $purchaseItem = new PurchaseItem;
       $purchaseItem->setPurchase($purchase)
        // C'est à dire que tu est une ligne de commande de cette commande qu'on de recuperer du formaulaire
        ->setProduct($cartItem->product)
        ->setProductName($cartItem->product->getName())
        ->setQuantity($cartItem->qty)
        ->setTotal($cartItem->getTotal())
        ->setProductPrice($cartItem->product->getPrice());
       // A chaque foi qu'on crée un achat on va incrémenter le total
        $total += $cartItem->getTotal();

        $this->em->persist($purchaseItem);
   }
   $purchase->setTotal($total);
   // 8. Nous allons enregistrer la commande (entityManger)
   $this->em->flush();

   $flashBag->add('success', 'La commande a bien été enregistrée');
   return new RedirectResponse($this->router->generate('purchase_index'));


  }
}
