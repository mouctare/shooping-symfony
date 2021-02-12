<?php

namespace App\Doctrine\Listener;

use App\Entity\Category;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorySlugListener 
{
  
   protected $slugger;

    public function __construct(SluggerInterface $slugger){
        $this->slugger = $slugger;
    }


    //public function prePersist(LifecycleEventArgs $event) {
    public function prePersist(Category $category, LifecycleEventArgs $event) {
        
        // $entity = $event->getObject(); Avec le typintage on a pas besoin de sedemande si l'entity est une instance de Product

        // if(!$entity instanceof Product) {
        //     return;
        // }
        
        if(empty($category->getSlug())){
            
            $category->setSlug(strtolower($this->slugger->slug($category->getName())));
        }

    }
}