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

//     \Stripe\Stripe::setApiKey('sk_test_51IFzX1LidspSVr1ROWWc9Yeh31vrMAB9j6CnTIsI2Z77ICEbuWyqb1NbsiNWIGJzGyzBEfDJnMQfkS5G4kZzZaqq00sZxpzhd2');
//   $intent =  \Stripe\PaymentIntent::create([
//         'amount' => $purchase->getTotal(),
//         'currency' => 'eur'

//     ]);
//    // dd($intent->client_secret);

 $intent = $stripeService->getPayementIntent($purchase);

 // Je remplace cette ligne par le service que j'ai cré

    return $this->render('purchase/payment.html.twig', [
        'clientSecret' => $intent->client_secret,
        'purchase' => $purchase,
        'stripPublicKey'=> $stripeService->getPublicKey()
    ]);

}
}