<?php
namespace App\Purchase;

use DateTime;
use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;


class PurchasePersister
{
    protected $security;
    protected  $em;
    protected  $cartService;
    



    public function __construct(Security $security, CartService $cartService,  EntityManagerInterface $em)
    {
      
        $this->security = $security;
        $this->cartService = $cartService;
        $this->em = $em;

    }

    public function storePurchase(Purchase $purchase)
    {
         // 6. Nous allons la lier ave cle user actuellement connecté (Security)
         $purchase->setUser($this->security->getUser())
         ->setPurchasedAt(new DateTime())
         ->setTotal($this->cartService->getTotal());
         $this->em->persist($purchase);

// 7. Nous allons la lier avec les produits qui sont dan le panier (CartService)


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
// A chaque fois qu'on crée un achat on va incrémenter le total


 $this->em->persist($purchaseItem);
}

// 8. Nous allons enregistrer la commande (entityManger)
$this->em->flush();

    }
}