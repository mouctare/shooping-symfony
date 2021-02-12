<?php
namespace App\Events;
use App\Entity\Purchase;
use Symfony\Contracts\EventDispatcher\Event;


class PurchaseSuccessEvent  extends Event{
    private $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

   

    /**
     * Get the value of purchase
     */ 
    public function getPurchase(): Purchase
    {
        return $this->purchase;
    }
}