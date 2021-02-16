<?php

namespace App\Controller\Purchase;

use App\Repository\PurchaseRepository;
use App\Stripe\StripeService;
use Stripe\Stripe;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PurchasePaymentController extends AbstractController
{
/**
 * @Route("/purchase/pay/{id}", name="purchase_payment_form")
 */
public function showCardForm($id, PurchaseRepository $purchaseRepository, StripeService $stripeService)
{

    $purchase = $purchaseRepository->find($id);

    if(!$purchase){
        return $this->redirectToRoute('cart_show');

    }



 //$intent = $stripeService->getPayementIntent($purchase);

 // Je remplace cette ligne par le service que j'ai cré

    return $this->render('purchase/payment.html.twig', [
      //  'clientSecret' => $intent->client_secret,
        'purchase' => $purchase,
        'stripPublicKey'=> $stripeService->getPublicKey()
    ]);

}
}