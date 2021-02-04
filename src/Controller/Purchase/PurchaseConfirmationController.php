<?php
namespace App\Controller\Purchase;

use DateTime;
use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class PurchaseConfirmationController extends AbstractController
{
  
    protected  $cartService;
    protected  $em;
    protected   $persister;


    public function __construct( CartService $cartService,  EntityManagerInterface $em,  PurchasePersister $persister)
    {
    
        $this->cartService = $cartService;
        $this->em = $em;
        $this->persister = $persister;

    }
    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="Vous devez étre connecté pour confirmez une commandes")
     */
  public function confirm(Request $request)
  {
      // Ici on met la request dans le méthode car la request est unique à chaque appelle
     // 1. Nous voulons lire les données du formulaire
     // FormFactoryInterface / Request
     //$form = $this->formFactory->create(CartConfirmationType::class);
     $form = $this->createForm(CartConfirmationType::class);
     
     $form->handleRequest($request);
     // 2. Si le formulaire n'a pas été soummis: dégager
     if(!$form->isSubmitted()) {
         // Message Flash redirection
       //  $flashBag->add('warning', 'Vous devez remplir le formulaire de confirmation');
         $this->addFlash('warning', 'Vous devez remplir le formulaire de confirmation');
        // return new RedirectResponse($this->router->generate('cart_show'));
         $this->redirectToRoute('cart_show');
     }

     // 3. si je ne suis pas connecté : dégager (Security)

    //  $user = $this->security->getUser();
    // $user =  $this->getUser(); c'est le isGranted qui le gère desormais

    // if(!$user){
      //  throw new AccessDeniedException("Vous devez étre connecté pour confirmez une commandes");
    //  }


     // 4. si il n'y a pas de de produits dans le panier : dégager (CartService)
     $cartItems = $this->cartService->getDetailedCartItems();

     if(count($cartItems) === 0){
        $this->addFlash('warning', 'Vous ne pouvez pas confirmez une comande avec un panier vide');
       // return new RedirectResponse($this->router->generate('cart_show'));
       $this->redirectToRoute('cart_show');
    }
  
     // 5. Nous allons créer une purchase 
    /**
     * @var Purchase
     */
      $purchase = $form->getData(); // Ici on récupère les ,donnée du formulaire
    $this->persister->storePurchase($purchase); // On stock la commande avec toutes les infos
  
  // $this->cartService->empty(); je ne vide plus le panierw

  // $this->addFlash('success', 'La commande a bien été enregistrée');
  // return new RedirectResponse($this->router->generate('purchase_index'));
  return $this->redirectToRoute('purchase_payment_form', [
    // Ici on redirige vers le formulaire de payement on appelle la route et on récupère l'id de la commande
    'id' => $purchase->getId()
  ]);


  }
}
