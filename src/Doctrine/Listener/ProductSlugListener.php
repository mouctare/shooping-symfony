<?php

namespace App\Doctrine\Listener;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductSlugListener 
{
  
   protected $slugger;

    public function __construct(SluggerInterface $slugger){
        $this->slugger = $slugger;
    }


    //public function prePersist(LifecycleEventArgs $event) {
    public function prePersist(Product $entity, LifecycleEventArgs $event) {
        
        // $entity = $event->getObject(); Avec le typintage on a pas besoin de sedemande si l'entity est une instance de Product

        // if(!$entity instanceof Product) {
        //     return;
        // }
        
        if(empty($entity->getSlug())){
            // SluggerInterface
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }

    }
}