<?php
namespace App\Events;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class ProductViewEvent extends Event {
    protected $product;

    // A chaque quon affiche un produit on va afficher un événement
    public function __construct(Product $product)
    {
        $this->product = $product;
        
    }
    

    /**
     * Get the value of product
     */ 
    public function getProduct() : Product
    {
        return $this->product;
    }
}