<?php

namespace App\Stripe;

use App\Entity\Purchase;

class StripeService
{
    protected $secretKey;
    protected $publicKey;
public function __construct(string $secretKey, string $publicKey)
{
   // dd($secretKey, $publicKey);
   $this->secretKey = $secretKey; 
   $this->publicKey = $publicKey;
}

public function getPublicKey(): string{
    return $this->publicKey;

}
    public function getPayementIntent(Purchase $purchase){
        //\Stripe\Stripe::setApiKey('sk_test_51IFzX1LidspSVr1ROWWc9Yeh31vrMAB9j6CnTIsI2Z77ICEbuWyqb1NbsiNWIGJzGyzBEfDJnMQfkS5G4kZzZaqq00sZxpzhd2');
        // Ici on remplace la secretKey écrite en dure en mettant en place un constructeur
        \Stripe\Stripe::setApiKey($this->secretKey);
        return \Stripe\PaymentIntent::create([
              'amount' => $purchase->getTotal(),
              'currency' => 'eur'

          ]);
         // dd($intent->client_secret);
    }
}