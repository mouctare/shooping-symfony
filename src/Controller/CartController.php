<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CartController extends AbstractController
{
    // Là on se rend compte que notre controlleur est trés dependant du repository et du cartService du coup on crée un constructeur pour bse les faires liverer

    protected  $productRepository;
    protected  $cartService;

    public function __construct(ProductRepository $productRepository, CartService $cartService)
    {
      $this->productRepository = $productRepository;
      $this->cartService = $cartService;
    }
    

    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id,   Request $request)
    {
    //0. Securisation : est-ce que le produit existe ?
    $product = $this->productRepository->find($id);

    if(!$product){
        throw $this->createNotFoundException("Le produit $id n'éxiste pas !");
    }
   
    $this->cartService->add($id);
  
  
 // $flashBag = $session->getBag('flashes'); avec la FlashBagInterface pas besoin daller la chercher soit meme

  $this->addFlash('success', "Le produit a bien été ajouté au pannier");
 // $flashBag->add('success', "Le produit a bien été ajouté au pannier"); Grace au racourci de l'abstractController
  
  // On lui rédirige vers le produit qu'il vie nt d'ajouter

  if($request->query->get('returnToCart')){
      // On fait ceci pour eviter le chagement de page l'ors de l'incrémentation
    return $this->redirectToRoute('cart_show');
  }

  return $this->redirectToRoute('product_show', [
      'category_slug' => $product->getCategory()->getSlug(),
      "slug" => $product->getSlug()
  ]);
  
}
/**
 * @Route("/cart", name="cart_show")
 */
public function show()
{
    $form = $this->createForm(CartConfirmationType::class);
 $detailedCart = $this->cartService->getDetailedCartItems();

 $total = $this->cartService->getTotal();
 
return $this->render('cart/index.html.twig', [
    'items' => $detailedCart,
    'total' => $total,
    'confirmationForm' => $form->createView()
]);
   
}

/**
 *  @Route("cart/delete/{id}", name="cart_delete", requirements={"id": "\d+"})
 */
// Ici on envie de savoir si l'identifiant qu'on reçoit existe du coup on le reçoi en paramaetre
public function delete($id ){

    $product = $this->productRepository->find($id);

    if(!$product){
        throw $this->createNotFoundException("Le produit $id n'existe pas et ne peut pas étre supprimé !");
    }
      
    $this->cartService->remove($id);
    $this->addFlash("success", "Le produit a bien été supprimé du panier");

    return $this->redirectToRoute("cart_show");

}
/**
 *  @Route("cart/decrement/{id}", name="cart_decrement", requirements={"id": "\d+"})
 */
 public function decrement($id){
    $product = $this->productRepository->find($id);

    if(!$product){
        throw $this->createNotFoundException("Le produit $id n'existe pas et ne peut pas étre decrémenté !");
    }
      
    $this->cartService->decrement($id);

    $this->addFlash("success", "Le produit a bien été decrémenté");

    return $this->redirectToRoute("cart_show");

 }

}
