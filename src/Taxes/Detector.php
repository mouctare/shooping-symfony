<?php

namespace App\Taxes;

class Detector
{
    protected $seuil;
    
    public function __construct($seuil)
    {
        // Mon seuil égal au seuil qu'on mm'a donné à la construction  $this->seuil = $seuil; 
       $this->seuil = $seuil; 
    }
    public function detect(float $prix) : bool
    {
        //if($prix > 100)
        if($prix > $this->seuil){
            return true;
        } else{
            return false;
        }
    }
}
