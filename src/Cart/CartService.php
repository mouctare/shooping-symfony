<?php

namespace App\Cart;
use App\Cart\CartItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartService
{
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session,  ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }
 
    // Ici aussi à chaque fois je rèpete la   $cart = $this->session->get('cart', []); don c je factorise

    protected function getCart() : array {
     return $this->session->get('cart', []);

    }

    public function empty()  {
        $this->saveCart([]);
     
         }
    protected function saveCart(array $cart)  {
      $this->session->set('cart', $cart);
   
       }

    public function add(int $id){
          // 1.Retoruver le panier dans la session(sous forme de tableau)

             // 2. Si il n'éxiste pas encore , alors prendre un tableau vide

            // $cart = $request->getSession()->get('cart', []);
            $cart = $this->getCart();    // $cart = $this->session->get('cart', []);


             // 3 .Voir si la produit ($id) existe déjà dan sle 
             // 5. Sinon, ajouter le produit avec al quantité 1
             // On cherche les clées

            //  if(array_key_exists($id, $cart)){
            //      $cart[$id]++;
            //  }else {
            //      $cart[$id] = 1;
            // // 4.Si c'est le cas , simplement augmenter la quantité
            //  }
            if(!array_key_exists($id, $cart)){
                $cart[$id]= 0;
            }
                $cart[$id]++;
         
          $this->saveCart($cart);      //$this->session->set('cart', $cart);

    }

    public function remove(int $id){
        $cart = $this->getCart();          //$this->session->get('cart', []);
        // On veut supprimé dans notre cart la donnée qui a la clée ou l'identifiant id
        unset($cart[$id]);

      $this->saveCart($cart);   // $this->session->set('cart', $cart);
    }

    public function  decrement(int $id){
        // On ouvre notre panier
        $cart = $this->getCart();     // $cart = $this->session->get('cart', []);
        // On demande si le produit éxiste

        if (!array_key_exists($id, $cart)) {     // si ce produit n'existe pas dans le pannier $cart sil nexiste pas je ne rien à faire
            return;
        }
        // Soit le produit est à 1 alors il faut sipmlement le supprimé

        if($cart[$id] === 1){  // s'il une quantité de 1 pour ce produit alors il faut le supprimé
            $this->remove($id);
            return;
        } 
         // Soit le produit est à plus de 1, alors il faut le décrémenter;
       
        $cart[$id]--;
        $this->saveCart( $cart);
    }

    public function getTotal() :  int {
        $total = 0;
        foreach($this->getCart('cart', []) as $id => $qty){
            $product = $this->productRepository->find($id);

            if(!$product){
                continue;
            }
              
             $total += ($product->getPrice() * $qty);
          }
          return $total;


    }
    /**
     *
     *
     * @return CartItem[]
     */
    public function getDetailedCartItems() :  array {
         // On crée un tableau vide pour créer le pannier
         $detailedCart = [];
         
      foreach($this->getCart('cart', []) as $id => $qty){
           $product = $this->productRepository->find($id);

           if(!$product){
            continue;
        }

             // On ajoute un produit à la cart
             $detailedCart[] = new CartItem($product, $qty);
            // $total += ($product->getPrice() * $qty);
         }

         return $detailedCart;

        
    }
}