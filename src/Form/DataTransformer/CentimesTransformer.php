<?php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;



class  CentimesTransformer implements DataTransformerInterface{
    public function transform($value)
    {
     // Cette methode agit avant l'envoit du formulaire
     if(null === $value){
        return;
     }
     return $value / 100;
    }
   

    public function reverseTransform($value)
    {
        if(null === $value){
            return;
         }
         return $value * 100; 
    }
    
}